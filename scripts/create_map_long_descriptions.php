<?php

/**
 * @file
 * DRUPALNASS-4 — Path B: build the "Map Long Description" taxonomy.
 *
 * Creates (all idempotent — safe to re-run):
 *   1. Vocabulary  map_long_desc            ("Map Long Descriptions")
 *   2. Term field  field_source_image        (PNG filename = join key to visuals nodes)
 *   3. ~48 terms parsed from node 12547 ("Long Description of Maps")
 *   4. Visuals field  field_long_description  (entity reference -> map_long_desc)
 *
 * This does NOT touch/populate the visuals nodes — that is a separate follow-up
 * script that matches each map node's image filename to a term's field_source_image.
 *
 * Config is created via the entity/config API on purpose (this project's
 * `drush cim` path is broken by orphaned D11 config — see project notes).
 *
 * Run:  ddev drush php:script scripts/create_map_long_descriptions.php
 * On server:  vendor/bin/drush php:script scripts/create_map_long_descriptions.php
 */

use Drupal\taxonomy\Entity\Vocabulary;
use Drupal\taxonomy\Entity\Term;
use Drupal\field\Entity\FieldStorageConfig;
use Drupal\field\Entity\FieldConfig;

$VID          = 'map_long_desc';
$VID_LABEL    = 'Map Long Descriptions';
$TERM_FIELD   = 'field_source_image';       // on the vocabulary term
$NODE_FIELD   = 'field_long_description';    // on node.visuals
$SOURCE_NID   = 12547;                       // "Long Description of Maps" landing_page
$SOURCE_FIELD = 'field_content';

$etm  = \Drupal::entityTypeManager();
$text_format = function_exists('filter_fallback_format') ? filter_fallback_format() : 'plain_text';

$created = ['vocab' => 0, 'term_field' => 0, 'node_field' => 0, 'terms' => 0, 'updated' => 0, 'skipped' => 0];
$warnings = [];

// ---------------------------------------------------------------------------
// 1. Vocabulary
// ---------------------------------------------------------------------------
if (!Vocabulary::load($VID)) {
  Vocabulary::create([
    'vid' => $VID,
    'name' => $VID_LABEL,
    'description' => 'Section 508 long descriptions for choropleth county maps (DRUPALNASS-4).',
  ])->save();
  $created['vocab']++;
  echo "Created vocabulary: $VID\n";
}
else {
  echo "Vocabulary already exists: $VID\n";
}

// ---------------------------------------------------------------------------
// 2. Term field: field_source_image (PNG filename join key)
// ---------------------------------------------------------------------------
if (!FieldStorageConfig::loadByName('taxonomy_term', $TERM_FIELD)) {
  FieldStorageConfig::create([
    'field_name' => $TERM_FIELD,
    'entity_type' => 'taxonomy_term',
    'type' => 'string',
    'settings' => ['max_length' => 255],
    'cardinality' => 1,
  ])->save();
  $created['term_field']++;
  echo "Created field storage: taxonomy_term.$TERM_FIELD\n";
}
if (!FieldConfig::loadByName('taxonomy_term', $VID, $TERM_FIELD)) {
  FieldConfig::create([
    'field_name' => $TERM_FIELD,
    'entity_type' => 'taxonomy_term',
    'bundle' => $VID,
    'label' => 'Source image filename',
    'description' => 'The map PNG filename (e.g. BR-HA-RGBChor.png). Used to link visuals map nodes to this description.',
    'required' => FALSE,
  ])->save();
  echo "Created field instance: taxonomy_term.$VID.$TERM_FIELD\n";
}

// ---------------------------------------------------------------------------
// 3. Parse node 12547 into {png, title, description}
// ---------------------------------------------------------------------------
$node = $etm->getStorage('node')->load($SOURCE_NID);
if (!$node || !$node->hasField($SOURCE_FIELD) || $node->get($SOURCE_FIELD)->isEmpty()) {
  echo "ABORT: source node $SOURCE_NID / $SOURCE_FIELD not found or empty.\n";
  return;
}
$html = (string) $node->get($SOURCE_FIELD)->value;

$entries = [];
if (preg_match_all('#<p>(.*?)</p>#is', $html, $blocks)) {
  foreach ($blocks[1] as $block) {
    if (!preg_match('#<strong>\s*(.*?\.png)\s*</strong>#i', $block, $m)) {
      continue;
    }
    $png = trim($m[1]);
    $parts = preg_split('#<br\s*/?>#i', $block);
    $title = isset($parts[1]) ? trim(html_entity_decode(strip_tags($parts[1]))) : '';
    $desc  = '';
    if (count($parts) > 2) {
      $desc = trim(html_entity_decode(strip_tags(implode(' ', array_slice($parts, 2)))));
    }
    $entries[$png] = ['png' => $png, 'title' => $title, 'description' => $desc];
  }
}
echo "\nParsed " . count($entries) . " map entries from node $SOURCE_NID.\n";

// ---------------------------------------------------------------------------
// 4. Create / update terms (idempotent, keyed on field_source_image)
// ---------------------------------------------------------------------------
$term_storage = $etm->getStorage('taxonomy_term');

// Index existing terms in this vocab by their PNG filename.
$existing = [];
$tids = $term_storage->getQuery()
  ->accessCheck(FALSE)
  ->condition('vid', $VID)
  ->execute();
foreach ($term_storage->loadMultiple($tids) as $t) {
  $key = $t->hasField($TERM_FIELD) ? (string) $t->get($TERM_FIELD)->value : '';
  if ($key !== '') {
    $existing[$key] = $t;
  }
}

foreach ($entries as $png => $e) {
  // Flag source content gaps but still create the term so nodes can reference it.
  if ($e['description'] === '') {
    $warnings[] = "MISSING DESCRIPTION in source for {$png}" . ($e['title'] ? " (\"{$e['title']}\")" : ' (title also missing)') . " — needs text from DDO.";
  }
  $name = $e['title'] !== '' ? $e['title'] : $png;

  if (isset($existing[$png])) {
    $term = $existing[$png];
    $term->setName($name);
    $term->set('description', ['value' => $e['description'], 'format' => $text_format]);
    $term->save();
    $created['updated']++;
    continue;
  }

  Term::create([
    'vid' => $VID,
    'name' => $name,
    'description' => ['value' => $e['description'], 'format' => $text_format],
    $TERM_FIELD => $png,
  ])->save();
  $created['terms']++;
}

// ---------------------------------------------------------------------------
// 5. Visuals reference field: field_long_description -> map_long_desc
//    (Remove this section if you prefer to add the field via the UI at
//     /admin/structure/types/manage/visuals/fields)
// ---------------------------------------------------------------------------
if (!FieldStorageConfig::loadByName('node', $NODE_FIELD)) {
  FieldStorageConfig::create([
    'field_name' => $NODE_FIELD,
    'entity_type' => 'node',
    'type' => 'entity_reference',
    'settings' => ['target_type' => 'taxonomy_term'],
    'cardinality' => 1,
  ])->save();
  $created['node_field']++;
  echo "Created field storage: node.$NODE_FIELD\n";
}
if (!FieldConfig::loadByName('node', 'visuals', $NODE_FIELD)) {
  FieldConfig::create([
    'field_name' => $NODE_FIELD,
    'entity_type' => 'node',
    'bundle' => 'visuals',
    'label' => 'Long description',
    'description' => 'Section 508 long description for this map (DRUPALNASS-4).',
    'required' => FALSE,
    'settings' => [
      'handler' => 'default:taxonomy_term',
      'handler_settings' => [
        'target_bundles' => [$VID => $VID],
        'sort' => ['field' => 'name', 'direction' => 'asc'],
        'auto_create' => FALSE,
      ],
    ],
  ])->save();
  echo "Created field instance: node.visuals.$NODE_FIELD -> $VID\n";
}

// ---------------------------------------------------------------------------
// Summary
// ---------------------------------------------------------------------------
echo "\n==================== SUMMARY ====================\n";
echo "Vocabulary created:      {$created['vocab']}\n";
echo "Term field created:      {$created['term_field']}\n";
echo "Visuals field created:   {$created['node_field']}\n";
echo "Terms created:           {$created['terms']}\n";
echo "Terms updated:           {$created['updated']}\n";
echo "Total terms in vocab:    " . count($term_storage->getQuery()->accessCheck(FALSE)->condition('vid', $VID)->execute()) . "\n";

if ($warnings) {
  echo "\n----- CONTENT GAPS (" . count($warnings) . ") — flag to DDO -----\n";
  foreach ($warnings as $w) {
    echo "  ! $w\n";
  }
}
echo "\nNext step: run the follow-up script to link each visuals map node to its\n";
echo "term by matching the node image filename against $TERM_FIELD.\n";
