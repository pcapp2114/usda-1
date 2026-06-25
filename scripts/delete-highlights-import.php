<?php

/**
 * Rollback / undo for scripts/import-highlights.php.
 *
 * Deletes the `highlights` nodes that the importer created — matched against the
 * SAME CSV by title + year AND verified against the exact local PDF link the
 * importer set, so it never touches an unrelated node that happens to share a
 * title. Deleting a node automatically removes it from the `highlights_` entity
 * queue. Optionally also deletes the downloaded PDF files.
 *
 * ===========================================================================
 *  HOW TO ROLL BACK  —  run from the project root
 * ===========================================================================
 *
 *    # 1. Back up the database first  (production)
 *    vendor/bin/drush sql:dump --gzip --result-file=$HOME/backup-highlights.sql.gz
 *
 *    # 2. DRY RUN  —  shows exactly which nodes will be deleted, changes nothing
 *    vendor/bin/drush php:script scripts/delete-highlights-import.php
 *
 *    # 3. Edit this file:        DRY_RUN = TRUE   ->   FALSE
 *    #    (optional) also delete the downloaded PDFs:   DELETE_PDFS = TRUE
 *
 *    # 4. REAL RUN  —  deletes the imported nodes (auto-removes them from the queue)
 *    vendor/bin/drush php:script scripts/delete-highlights-import.php
 *
 *    # 5. Rebuild caches
 *    vendor/bin/drush cr
 *
 *    # 6. Set DRY_RUN back to TRUE
 *
 *  -------------------------------------------------------------------------
 *  HOW TO RE-IMPORT  (after rolling back) — see the companion script:
 *    vendor/bin/drush php:script scripts/import-highlights.php
 *  -------------------------------------------------------------------------
 *
 *  Local dev: use  `ddev drush ...`  in place of  `vendor/bin/drush ...`.
 * ===========================================================================
 */

use Drupal\Core\File\FileSystemInterface;

const DRY_RUN     = TRUE;    // <-- set to FALSE to actually delete
const DELETE_PDFS = FALSE;   // <-- set to TRUE to also remove the downloaded PDFs
const CSV_REL     = '/scripts/migration/highlights-2025-2026.csv';
const NODE_TYPE   = 'highlights';
const SUBQUEUE_ID = 'highlights_';
const DEST_BASE   = 'public://Publications/Highlights';
const LINK_BASE   = 'internal:/sites/default/files/Publications/Highlights';

$csv_path = DRUPAL_ROOT . '/..' . CSV_REL;
if (!is_file($csv_path)) {
  echo "ERROR: CSV not found at $csv_path\n";
  return;
}

$etm         = \Drupal::entityTypeManager();
$nodes       = $etm->getStorage('node');
$file_system = \Drupal::service('file_system');

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

// --- Match nodes created by this import -------------------------------------
$delete_nids  = [];   // nid => label
$delete_files = [];   // nid => file uri
$not_found    = [];
$mismatch     = [];

foreach ($rows as $i => $r) {
  $title = trim((string) ($r['title'] ?? ''));
  $year  = trim((string) ($r['year'] ?? ''));
  $url   = trim((string) ($r['url'] ?? ''));
  if ($title === '' || $year === '') {
    continue;
  }

  $filename      = basename((string) parse_url($url, PHP_URL_PATH));
  $expected_link = LINK_BASE . '/' . $year . '/' . $filename;
  $file_uri      = DEST_BASE . '/' . $year . '/' . $filename;

  $ids = $nodes->getQuery()
    ->condition('type', NODE_TYPE)
    ->condition('title', $title)
    ->condition('field_year', $year)
    ->accessCheck(FALSE)
    ->execute();

  if (!$ids) {
    $not_found[] = "$title ($year)";
    continue;
  }

  foreach ($ids as $nid) {
    $node = $nodes->load($nid);
    $link = $node->get('field_pdf_file')->isEmpty() ? '' : (string) $node->get('field_pdf_file')->first()->uri;
    if ($link === $expected_link) {
      $delete_nids[$nid]  = "$title ($year)";
      $delete_files[$nid] = $file_uri;
    }
    else {
      $mismatch[] = "node $nid \"$title\" ($year) — PDF link is not from this import; NOT deleting";
    }
  }
}

// --- Report -----------------------------------------------------------------
echo 'Matched (created by this import) -> will delete: ' . count($delete_nids) . "\n";
echo 'Not present (nothing to delete):               ' . count($not_found) . "\n";
echo 'Found but link mismatch (left alone):          ' . count($mismatch) . "\n";
foreach ($delete_nids as $nid => $label) {
  echo "  DELETE node $nid: $label\n";
}
foreach ($mismatch as $m) {
  echo "  SKIP: $m\n";
}

$subqueue = $etm->getStorage('entity_subqueue')->load(SUBQUEUE_ID);
$queue_before = $subqueue ? count($subqueue->get('items')->getValue()) : 0;
echo "\nQueue '" . SUBQUEUE_ID . "' size before: $queue_before\n";

if (DRY_RUN) {
  echo "\n=== DRY RUN — nothing deleted. Set DRY_RUN = false to execute"
    . (DELETE_PDFS ? ' (and DELETE_PDFS is ON: PDFs would also be removed)' : '') . ". ===\n";
  return;
}

if (!$delete_nids) {
  echo "\nNothing to delete.\n";
  return;
}

// --- Delete nodes (auto-removes them from the entity queue) ------------------
$nodes->delete($nodes->loadMultiple(array_keys($delete_nids)));
echo "\nDeleted " . count($delete_nids) . " highlights node(s).\n";

// --- Optionally remove the downloaded PDFs ----------------------------------
$pdfs_removed = 0;
if (DELETE_PDFS) {
  foreach ($delete_files as $uri) {
    if (is_file($file_system->realpath($uri))) {
      $file_system->delete($uri);
      $pdfs_removed++;
    }
  }
  echo "Removed $pdfs_removed downloaded PDF file(s).\n";
}
else {
  echo "Left downloaded PDF files in place (DELETE_PDFS = false).\n";
}

$subqueue = $etm->getStorage('entity_subqueue')->load(SUBQUEUE_ID);
$queue_after = $subqueue ? count($subqueue->get('items')->getValue()) : 0;
echo "Queue '" . SUBQUEUE_ID . "' size after: $queue_after (auto-removed " . ($queue_before - $queue_after) . ").\n";

echo "\nDONE.\n";
