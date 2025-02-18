<?php

/**
 * @file
 * Drush integration for the sync_uuids module.
 */

use Drupal\sync_uuids\Commands\SyncUuidsCommands;

/**
 * Implements hook_drush_command().
 */
function sync_uuids_drush_command() {
  return [
    'sync-uuids' => [
      'aliases' => ['su'],
      'description' => dt('Sync the UUIDs of all active config in DB with the UUIDs found in config files.'),
      'bootstrap' => DRUSH_BOOTSTRAP_DRUPAL_DATABASE,
      'drupal dependencies' => ['config'],
      'core' => ['8+'],
    ],
  ];
}

/**
 * Implements hook_drush_help().
 */
function sync_uuids_drush_help($command) {
  switch ($command) {
    case 'sync-uuids':
      return dt('Sync the UUIDs of all active config in DB with the UUIDS found in config files.');
  }
}

/**
 * Command callback: Sync config UUIDs.
 */
function drush_sync_uuids() {
  try {
    $sync = new SyncUuidsCommands();
    return $sync->syncUuids();
  }
  catch (Exception $e) {
    return drush_set_error('DRUSH_CONFIG_ERROR', $e->getMessage());
  }
}
