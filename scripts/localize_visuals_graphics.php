<?php

/**
 * @file
 * DRUPALNASS-5 — Consolidate visuals graphics onto ONE convention.
 *
 * Goal: every image-bearing `visuals` node stores its image as a LOCAL relative
 * path in field_visuals_image_delta_path (/sites/default/files/...), so the
 * template needs a single code path instead of the if/elseif guesswork.
 *
 * Transformations (all idempotent — safe to re-run):
 *   A. External nass.usda.gov delta_paths (~33): download the PNG into the files
 *      dir, rewrite delta_path to the local relative path.
 *   B. QA-host delta_path (node 42388): strip the bad hostname -> local relative
 *      path; if the file is missing locally, try to fetch it from nass.usda.gov.
 *   C. Managed-image nodes (~51, field_visual_image -> public://visuals/X.png):
 *      write the derived /sites/default/files/visuals/X.png into delta_path
 *      (ONLY where delta_path is empty, so nodes that already have one are kept).
 *   D. Relative-path nodes (~272): already correct, untouched.
 *
 * Run BEFORE the single-path template takes effect on any environment.
 * Content + downloaded files do NOT deploy via git, so run this on each env
 * (incl. production). Config-apply is via entity API here (drush cim is broken).
 *
 * Run:  ddev drush php:script scripts/localize_visuals_graphics.php
 * Server:  vendor/bin/drush php:script scripts/localize_visuals_graphics.php
 */

use Drupal\Core\StreamWrapper\StreamWrapperManagerInterface;

$DELTA_FIELD = 'field_visuals_image_delta_path';
$IMG_FIELD   = 'field_visual_image';
$NASS_HOST   = 'https://www.nass.usda.gov';
$LOCAL_BASE  = '/sites/default/files';

$etm        = \Drupal::entityTypeManager();
$node_store = $etm->getStorage('node');
$file_store = $etm->getStorage('file');
$file_system = \Drupal::service('file_system');
$http = \Drupal::httpClient();

$stats = ['downloaded' => 0, 'ext_rewritten' => 0, 'qa_fixed' => 0, 'managed_converted' => 0, 'skipped' => 0, 'failed' => 0, 'no_image' => 0];
$warnings = [];

/**
 * Download a URL into a public:// destination if not already present.
 * Returns TRUE if the file exists locally afterward.
 */
$ensure_local_file = function (string $url, string $public_uri) use ($file_system, $http, &$stats, &$warnings): bool {
  $real = $file_system->realpath($public_uri);
  if ($real && file_exists($real)) {
    return TRUE;
  }
  $dir = dirname($public_uri);
  $file_system->prepareDirectory($dir, \Drupal\Core\File\FileSystemInterface::CREATE_DIRECTORY);
  try {
    $data = (string) $http->get($url, ['timeout' => 60])->getBody();
    if ($data === '') {
      $warnings[] = "Empty download: $url";
      return FALSE;
    }
    $file_system->saveData($data, $public_uri, \Drupal\Core\File\FileSystemInterface::EXISTS_REPLACE);
    $stats['downloaded']++;
    return TRUE;
  }
  catch (\Throwable $e) {
    $warnings[] = "Download FAILED: $url ({$e->getMessage()})";
    return FALSE;
  }
};

$nids = $node_store->getQuery()->accessCheck(FALSE)->condition('type', 'visuals')->execute();
echo "Scanning " . count($nids) . " visuals nodes...\n\n";

foreach (array_chunk($nids, 50) as $chunk) {
  foreach ($node_store->loadMultiple($chunk) as $node) {
    $delta = $node->hasField($DELTA_FIELD) && !$node->get($DELTA_FIELD)->isEmpty()
      ? trim((string) $node->get($DELTA_FIELD)->value) : '';

    // --- A. External nass.usda.gov URL -> download + localize ---------------
    if (str_starts_with($delta, $NASS_HOST . '/')) {
      $path = parse_url($delta, PHP_URL_PATH);              // /Charts_and_Maps/graphics/X.png
      $public_uri = 'public://' . ltrim($path, '/');        // public://Charts_and_Maps/graphics/X.png
      $ensure_local_file($delta, $public_uri);
      $node->set($DELTA_FIELD, $LOCAL_BASE . $path);        // /sites/default/files/Charts_and_Maps/graphics/X.png
      $node->save();
      $stats['ext_rewritten']++;
      continue;
    }

    // --- B. QA-host URL -> strip host, keep local path, backfill file --------
    if (preg_match('#^https?://qa\.nass\.[^/]+(/sites/default/files/.*)$#i', $delta, $m)) {
      $local = $m[1];                                       // /sites/default/files/Charts_and_Maps/graphics/clheadx2.png
      $public_uri = 'public://' . ltrim(preg_replace('#^/sites/default/files/#', '', $local), '/');
      $real = $file_system->realpath($public_uri);
      if (!($real && file_exists($real))) {
        // Try the same filename from the real NASS host.
        $ensure_local_file($NASS_HOST . preg_replace('#^/sites/default/files#', '', $local), $public_uri);
      }
      $node->set($DELTA_FIELD, $local);
      $node->save();
      $stats['qa_fixed']++;
      continue;
    }

    // --- Any other absolute URL we didn't expect: flag, don't touch ----------
    if (preg_match('#^https?://#i', $delta)) {
      $warnings[] = "Unhandled external delta_path on node {$node->id()}: $delta";
      $stats['skipped']++;
      continue;
    }

    // --- C. No delta_path but has a managed image -> derive local path -------
    if ($delta === '' && $node->hasField($IMG_FIELD) && !$node->get($IMG_FIELD)->isEmpty()) {
      $fid = $node->get($IMG_FIELD)->target_id;
      $file = $fid ? $file_store->load($fid) : NULL;
      if ($file) {
        $uri = $file->getFileUri();                          // public://visuals/X.png
        $local = preg_replace('#^public://#', $LOCAL_BASE . '/', $uri);  // /sites/default/files/visuals/X.png
        $node->set($DELTA_FIELD, $local);
        $node->save();
        $stats['managed_converted']++;
      }
      else {
        $warnings[] = "Node {$node->id()} field_visual_image references missing file (fid $fid)";
        $stats['failed']++;
      }
      continue;
    }

    // --- D. Already a relative local path -> in convention, leave it ---------
    if (str_starts_with($delta, $LOCAL_BASE . '/')) {
      $stats['skipped']++;
      continue;
    }

    // No image of any kind (iframe-only visuals, etc.)
    if ($delta === '') {
      $stats['no_image']++;
    }
  }
}

// ---------------------------------------------------------------------------
// Coverage check: any node with an image but still no local delta_path?
// ---------------------------------------------------------------------------
$leftover = [];
foreach (array_chunk($nids, 50) as $chunk) {
  foreach ($node_store->loadMultiple($chunk) as $node) {
    $delta = $node->hasField($DELTA_FIELD) && !$node->get($DELTA_FIELD)->isEmpty()
      ? trim((string) $node->get($DELTA_FIELD)->value) : '';
    $has_managed = $node->hasField($IMG_FIELD) && !$node->get($IMG_FIELD)->isEmpty();
    if ($has_managed && !str_starts_with($delta, $LOCAL_BASE . '/')) {
      $leftover[] = "nid {$node->id()} has an image but delta_path is '{$delta}'";
    }
  }
}

// ---------------------------------------------------------------------------
echo "==================== SUMMARY ====================\n";
echo "External nass URLs rewritten:   {$stats['ext_rewritten']}\n";
echo "  (files downloaded:            {$stats['downloaded']})\n";
echo "QA-host nodes fixed:            {$stats['qa_fixed']}\n";
echo "Managed images -> delta_path:   {$stats['managed_converted']}\n";
echo "Already-relative (untouched):   {$stats['skipped']}\n";
echo "No image (iframe/etc.):         {$stats['no_image']}\n";
echo "Failed:                         {$stats['failed']}\n";

if ($warnings) {
  echo "\n----- WARNINGS (" . count($warnings) . ") -----\n";
  foreach ($warnings as $w) { echo "  ! $w\n"; }
}
if ($leftover) {
  echo "\n----- ⚠️ COVERAGE GAPS (" . count($leftover) . ") — do NOT ship the single-path template until 0 -----\n";
  foreach ($leftover as $l) { echo "  ! $l\n"; }
}
else {
  echo "\n✅ Coverage OK: every image-bearing node now has a local delta_path. Single-path template is safe.\n";
}
