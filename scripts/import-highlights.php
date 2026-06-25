<?php

/**
 * Import the missing 2025/2026 "highlights" nodes from a CSV (no Feeds), with
 * each PDF DOWNLOADED into the local files directory (matching the original
 * highlights, which link to internal:/sites/default/files/... copies), then add
 * the nodes to the top of the "highlights_" entity queue.
 *
 * Reads scripts/migration/highlights-2025-2026.csv (columns: year,tag,title,url).
 * The CSV `url` is the SOURCE to download from; each PDF is saved locally to:
 *   public://Publications/Highlights/<year>/<filename>
 * and the node's field_pdf_file links to:
 *   internal:/sites/default/files/Publications/Highlights/<year>/<filename>
 *
 * ===========================================================================
 *  HOW TO USE  —  run from the project root
 * ===========================================================================
 *
 *    # 1. Back up the database first  (production)
 *    vendor/bin/drush sql:dump --gzip --result-file=$HOME/backup-highlights.sql.gz
 *
 *    # 2. DRY RUN  —  shows what it will download/create, changes nothing
 *    vendor/bin/drush php:script scripts/import-highlights.php
 *
 *    # 3. Edit this file:        DRY_RUN = TRUE   ->   FALSE
 *
 *    # 4. REAL RUN  —  downloads PDFs, creates nodes, adds them to the queue
 *    vendor/bin/drush php:script scripts/import-highlights.php
 *
 *    # 5. Rebuild caches
 *    vendor/bin/drush cr
 *
 *    # 6. Set DRY_RUN back to TRUE
 *
 *  -------------------------------------------------------------------------
 *  HOW TO ROLL BACK  (undo this import) — see the companion script:
 *    vendor/bin/drush php:script scripts/delete-highlights-import.php
 *  -------------------------------------------------------------------------
 *
 *  Local dev: use  `ddev drush ...`  in place of  `vendor/bin/drush ...`.
 *  Safe to re-run: nodes with the same title + year are skipped (no duplicates).
 * ===========================================================================
 */

use Drupal\Core\File\FileSystemInterface;

const DRY_RUN     = TRUE;   // <-- set to FALSE to actually download + create + queue
const CSV_REL     = '/scripts/migration/highlights-2025-2026.csv';
const NODE_TYPE   = 'highlights';
const TAG_VOCAB   = 'tags';
const SUBQUEUE_ID = 'highlights_';
// Local destination (public:// = sites/default/files). Files are grouped by year.
const DEST_BASE   = 'public://Publications/Highlights';
const LINK_BASE   = 'internal:/sites/default/files/Publications/Highlights';

$csv_path = DRUPAL_ROOT . '/..' . CSV_REL;
if (!is_file($csv_path)) {
  echo "ERROR: CSV not found at $csv_path\n";
  return;
}

$etm         = \Drupal::entityTypeManager();
$nodes       = $etm->getStorage('node');
$terms       = $etm->getStorage('taxonomy_term');
$file_system = \Drupal::service('file_system');
$http        = \Drupal::httpClient();

// --- Read the CSV -----------------------------------------------------------
$rows = [];
$handle = fopen($csv_path, 'r');
$header = fgetcsv($handle);
while (($data = fgetcsv($handle)) !== FALSE) {
  if (count(array_filter($data, static fn($v) => trim((string) $v) !== '')) === 0) {
    continue;
  }
  $rows[] = array_combine($header, $data);
}
fclose($handle);
echo 'Rows in CSV: ' . count($rows) . "\n\n";

// --- Resolve tag term ids by name (cached) ----------------------------------
$tag_cache = [];
$resolve_tag = static function (string $name) use (&$tag_cache, $terms): ?int {
  $name = trim($name);
  if ($name === '') {
    return NULL;
  }
  if (array_key_exists($name, $tag_cache)) {
    return $tag_cache[$name];
  }
  $ids = $terms->getQuery()
    ->condition('vid', TAG_VOCAB)
    ->condition('name', $name)
    ->accessCheck(FALSE)
    ->range(0, 1)
    ->execute();
  return $tag_cache[$name] = $ids ? (int) reset($ids) : NULL;
};

// --- Process rows -----------------------------------------------------------
$new_nids = [];   // created node ids, in CSV order (2026 then 2025)
$skipped  = [];
$errors   = [];

foreach ($rows as $i => $r) {
  $line  = $i + 2;
  $title = trim((string) ($r['title'] ?? ''));
  $year  = trim((string) ($r['year'] ?? ''));
  $tag   = trim((string) ($r['tag'] ?? ''));
  $url   = trim((string) ($r['url'] ?? ''));

  if ($title === '' || $year === '' || $url === '') {
    $errors[] = "line $line: missing title/year/url";
    continue;
  }

  // Duplicate guard.
  $existing = $nodes->getQuery()
    ->condition('type', NODE_TYPE)
    ->condition('title', $title)
    ->condition('field_year', $year)
    ->accessCheck(FALSE)
    ->range(0, 1)
    ->execute();
  if ($existing) {
    $skipped[] = "$title ($year) — already exists as node " . reset($existing);
    continue;
  }

  // Derive local destination + internal link from the source URL.
  $filename  = basename((string) parse_url($url, PHP_URL_PATH));
  $dest_dir  = DEST_BASE . '/' . $year;
  $dest_uri  = $dest_dir . '/' . $filename;
  $link_uri  = LINK_BASE . '/' . $year . '/' . $filename;
  $tid       = $resolve_tag($tag);
  if ($tag !== '' && $tid === NULL) {
    $errors[] = "line $line: tag '$tag' not found in '" . TAG_VOCAB . "' vocab (node created without a tag)";
  }

  if (DRY_RUN) {
    echo "WOULD: download $url\n";
    echo "         -> $dest_uri\n";
    echo "         create [$year][$tag] $title  (link: $link_uri)\n";
    $new_nids[] = "(dry-run)";
    continue;
  }

  // Download the PDF into the local files directory.
  try {
    $file_system->prepareDirectory($dest_dir, FileSystemInterface::CREATE_DIRECTORY | FileSystemInterface::MODIFY_PERMISSIONS);
    $body = (string) $http->get($url, ['timeout' => 60])->getBody();
    if ($body === '') {
      throw new \RuntimeException('empty response');
    }
    $file_system->saveData($body, $dest_uri, FileSystemInterface::EXISTS_REPLACE);
  }
  catch (\Throwable $e) {
    $errors[] = "line $line: could not download $url — " . $e->getMessage() . " (node NOT created)";
    continue;
  }

  // Create the node linking to the LOCAL copy.
  $values = [
    'type'           => NODE_TYPE,
    'title'          => $title,
    'status'         => 1,
    'field_year'     => $year,
    'field_pdf_file' => ['uri' => $link_uri, 'title' => $title],
  ];
  if ($tid) {
    $values['field_tag'] = ['target_id' => $tid];
  }
  $node = $nodes->create($values);
  $node->save();
  $new_nids[] = (int) $node->id();
  echo 'CREATED node ' . $node->id() . ": [$year] $title  (pdf saved + linked locally)\n";
}

// --- Summary ----------------------------------------------------------------
$real_nids = array_values(array_filter($new_nids, 'is_int'));
echo "\nSummary:\n";
echo '  created: ' . (DRY_RUN ? count($new_nids) . ' (dry run — nothing downloaded/created)' : count($real_nids)) . "\n";
echo '  skipped (already exist): ' . count($skipped) . "\n";
foreach ($skipped as $s) {
  echo "    SKIP: $s\n";
}
echo '  warnings/errors: ' . count($errors) . "\n";
foreach ($errors as $e) {
  echo "    ! $e\n";
}

// --- Add new nodes to the TOP of the entity subqueue ------------------------
if (DRY_RUN) {
  echo "\n(DRY RUN: would prepend " . count($new_nids) . " new nodes to the top of subqueue '" . SUBQUEUE_ID . "')\n";
}
elseif ($real_nids) {
  $subqueue = $etm->getStorage('entity_subqueue')->load(SUBQUEUE_ID);
  if ($subqueue) {
    $current = array_map('intval', array_column($subqueue->get('items')->getValue(), 'target_id'));
    $merged  = array_merge($real_nids, $current);
    $subqueue->set('items', $merged);
    $subqueue->save();
    echo "\nQueued " . count($real_nids) . " new nodes at the TOP of '" . SUBQUEUE_ID . "' (queue total now " . count($merged) . ").\n";
  }
  else {
    echo "\nWARNING: could not load subqueue '" . SUBQUEUE_ID . "' — add the new nodes to the queue manually.\n";
  }
}

echo "\nDONE" . (DRY_RUN ? ' (dry run — no changes made).' : '.') . "\n";
