<?php

namespace Drupal\nass_tools\Commands;

use Drupal\Core\Database\Connection;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drush\Commands\DrushCommands;

class NassToolsCommands extends DrushCommands {

  protected EntityTypeManagerInterface $entityTypeManager;
  protected Connection $database;
  protected $channelLogger;

  public function __construct(
    EntityTypeManagerInterface $entity_type_manager,
    Connection $database,
                               $logger_factory
  ) {
    parent::__construct();
    $this->entityTypeManager = $entity_type_manager;
    $this->database = $database;
    $this->channelLogger = $logger_factory->get('nass_tools');
  }

  /**
   * @command nass:ping
   * @aliases nping
   */
  public function ping(): void {
    $message = 'NASS Tools Drush commands are working.';
    $this->logger()->success($message);
    $this->channelLogger->notice($message);
  }

}
