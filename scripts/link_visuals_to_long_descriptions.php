<?php

/**
 * @file
 * DRUPALNASS-4 — Path B, step 2: link visuals map nodes to their long-description term.
 *
 * For every `visuals` node, work out the map image filename (from the
 * field_visuals_image_delta_path string, or the uploaded field_visual_image),
 * find the map_long_desc term whose field_source_image matches, and set the
 * node's field_long_description reference.
 *
 * Idempotent — safe to re-run. Re-run this after DDO supplies the missing
 * descriptions and you re-run create_map_long_descriptions.php; the newly
 * covered maps will link automatically.
 *
 * Run:  ddev drush php:script scripts/link_visuals_to_long_descriptions.php
 * On server:  vendor/bin/drush php:script scripts/link_visuals_to_long_descriptions.php
 */

$VID        = 'map_long_desc';
$TERM_FIELD = 'field_source_image';
$NODE_FIELD = 'field_long_description';
$IMG_DELTA  = 'field_visuals_image_delta_path';
$IMG_UPLOAD = 'field_visual_image';

$etm          = \Drupal::entityTypeManager();
$term_storage = $etm->getStorage('taxonomy_term');
$node_storage = $etm->getStorage('node');
$file_storage = $etm->getStorage('file');

// ---------------------------------------------------------------------------
// Build a lookup: lowercased PNG filename -> term id.
// ---------------------------------------------------------------------------
$map = [];
$tids = $term_storage->getQuery()->accessCheck(FALSE)->condition('vid', $VID)->execute();
foreach ($term_storage->loadMultiple($tids) as $term) {
  $fn = $term->hasField($TERM_FIELD) ? trim((string) $term->get($TERM_FIELD)->value) : '';
  if ($fn !== '') {
    $map[strtolower($fn)] = $term->id();
  }
}
echo "Loaded " . count($map) . " terms with a source-image filename.\n\n";

// ---------------------------------------------------------------------------
// Walk every visuals node.
// ---------------------------------------------------------------------------
$stats = ['linked' => 0, 'already' => 0, 'gap_no_term' => 0, 'skipped_no_image' => 0];
$gaps = [];

$nids = $node_storage->getQuery()->accessCheck(FALSE)->condition('type', 'visuals')->execute();
foreach (array_chunk($nids, 50) as $chunk) {
  foreach ($node_storage->loadMultiple($chunk) as $node) {

    // Collect candidate image filenames for this node.
    $candidates = [];
    if ($node->hasField($IMG_DELTA) && !$node->get($IMG_DELTA)->isEmpty()) {
      $val = (string) $node->get($IMG_DELTA)->value;
      if ($val !== '') {
        $candidates[] = basename(parse_url($val, PHP_URL_PATH) ?: $val);
      }
    }
    if ($node->hasField($IMG_UPLOAD) && !$node->get($IMG_UPLOAD)->isEmpty()) {
      $fid = $node->get($IMG_UPLOAD)->target_id;
      if ($fid && ($file = $file_storage->load($fid))) {
        $candidates[] = $file->getFilename();
      }
    }

    if (!$candidates) {
      $stats['skipped_no_image']++;
      continue;
    }

    // Find the first candidate that matches a term.
    $tid = NULL;
    $matched_name = NULL;
    foreach ($candidates as $c) {
      $key = strtolower(trim($c));
      if (isset($map[$key])) {
        $tid = $map[$key];
        $matched_name = $c;
        break;
      }
    }

    if (!$tid) {
      // Only treat choropleth images as real gaps; ignore non-map visuals.
      $is_map = (bool) preg_grep('/chor/i', $candidates);
      if ($is_map) {
        $stats['gap_no_term']++;
        $gaps[] = "nid {$node->id()}  \"{$node->label()}\"  [" . implode(', ', $candidates) . ']';
      }
      else {
        $stats['skipped_no_image']++;
      }
      continue;
    }

    // Set the reference (skip if already correct).
    $current = $node->hasField($NODE_FIELD) && !$node->get($NODE_FIELD)->isEmpty()
      ? $node->get($NODE_FIELD)->target_id : NULL;
    if ((string) $current === (string) $tid) {
      $stats['already']++;
      continue;
    }
    $node->set($NODE_FIELD, ['target_id' => $tid]);
    $node->save();
    $stats['linked']++;
  }
}

// ---------------------------------------------------------------------------
// Summary
// ---------------------------------------------------------------------------
echo "==================== SUMMARY ====================\n";
echo "Newly linked:               {$stats['linked']}\n";
echo "Already linked (unchanged): {$stats['already']}\n";
echo "Map nodes with NO term:     {$stats['gap_no_term']}\n";
echo "Non-map / no image:         {$stats['skipped_no_image']}\n";

if ($gaps) {
  echo "\n----- MAP NODES STILL NEEDING A DESCRIPTION (" . count($gaps) . ") — flag to DDO -----\n";
  foreach ($gaps as $g) {
    echo "  ! $g\n";
  }
}
echo "\nNext: update node--visuals.html.twig to render the linked term's\n";
echo "description as an accessible long-description ([D] link / aria-describedby).\n";
