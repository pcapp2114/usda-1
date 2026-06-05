<?php

/**
 * Removes redundant Content Status filters from the Content and Recent content Views.
 *
 * Run:
 *   ddev drush scr scripts/nass_fix_content_status_filter_views.php
 *   ddev drush cr
 *   ddev drush cex -y
 */

$view_ids = [
  'content',
  'content_recent',
];

$storage = \Drupal::entityTypeManager()->getStorage('view');

foreach ($view_ids as $view_id) {
  $view = $storage->load($view_id);

  if (!$view) {
    echo "View not found: {$view_id}\n";
    continue;
  }

  $changed = FALSE;
  $displays = $view->get('display');

  foreach ($displays as $display_id => &$display) {
    if (empty($display['display_options']['filters'])) {
      continue;
    }

    foreach ($display['display_options']['filters'] as $filter_id => $filter) {
      $plugin_id = $filter['plugin_id'] ?? '';
      $field = $filter['field'] ?? '';

      if ($filter_id === 'status_extra' || $plugin_id === 'node_status' || $field === 'status_extra') {
        unset($display['display_options']['filters'][$filter_id]);
        $changed = TRUE;
        echo "Removed {$filter_id} from {$view_id}:{$display_id}\n";
      }
    }
  }

  if ($changed) {
    $view->set('display', $displays);
    $view->save();
    echo "Saved view: {$view_id}\n";
  }
  else {
    echo "No redundant status filter found in view: {$view_id}\n";
  }
}

echo "Done.\n";
