<?php

namespace Drupal\sync_uuids\Commands;

use Drupal\Core\Site\Settings;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;
use Drush\Commands\DrushCommands;

/**
 * Class SyncUuidsCommands.
 *
 * @package Drupal\sync_uuids\Commands
 */
class SyncUuidsCommands extends DrushCommands {

  /**
   * Sync the UUIDs of all active config in DB with the UUIDs found in config files.
   *
   * @command sync-uuids
   * @aliases su
   * @usage drush su
   *   Sync the UUIDs of all active config in DB with the UUIDs found in config files.
   */
  public function SyncUuids() {
    if ($this->io()
      ->confirm('Do you want to overwrite the existing UUIDs with the configs?')) {
      $finder = new Finder();
      $yamls = $finder->name('*.yml')
        ->in(Settings::get('config_sync_directory'))
        ->files();

      $factory = \Drupal::configFactory();
      foreach ($yamls as $file) {
        $key = $file->getBasename('.yml');
        $config = Yaml::parse(file_get_contents($file->getRealPath()));
        $active = $factory->getEditable($key);
        $uuid = $active->get('uuid');
        if (isset($uuid) && isset($config['uuid']) && ($uuid != $config['uuid'])) {
          $this->output()
            ->writeln(sprintf('Updated: %s (%s => %s)', $key, $active->get('uuid'), $config['uuid']));
          $active->set('uuid', $config['uuid'])->save();
        }
      }
    }
  }

}
