<?php

declare(strict_types=1);

namespace Drupal\agcensus_migrator\Commands;

use Drupal\agcensus_migrator\Service\AgCensusImporter;
use Drush\Commands\DrushCommands;

/**
 * Drush commands for Ag Census imports.
 */
final class AgCensusCommands extends DrushCommands {

  public function __construct(
    private readonly AgCensusImporter $importer,
  ) {
    parent::__construct();
  }

  /**
   * Import one normalized Ag Census JSON file.
   *
   * @command agcensus:import
   * @aliases agci
   *
   * @option no-download Skip remote file downloads.
   * @usage drush agcensus:import /tmp/2022-census.json
   */
  public function import(string $path, array $options = ['no-download' => FALSE]): void {
    $download = empty($options['no-download']);
    $result = $this->importer->importFromJsonFile($path, $download);
    $this->io()->success(sprintf(
      'Imported node %s (nid %s). Sections: %s. Items: %s. Downloaded files: %s.',
      $result['title'],
      $result['nid'],
      $result['sections'],
      $result['items'],
      $result['downloaded_files'],
    ));
  }
}
