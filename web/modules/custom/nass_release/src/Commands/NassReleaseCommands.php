<?php

namespace Drupal\nass_release\Commands;

use Drupal\nass_release\Service\ReleaseRepository;
use Drupal\nass_release\Service\TxSnapshotManager;
use Drush\Commands\DrushCommands;

final class NassReleaseCommands extends DrushCommands {

  public function __construct(
    private readonly TxSnapshotManager $snapshotManager,
    private readonly ReleaseRepository $repository,
  ) {
    parent::__construct();
  }

  /**
   * Scans TX files and rebuilds the TX snapshot when files changed.
   *
   * @command nass-release:scan-tx
   * @option force Force snapshot rebuild even if files did not change.
   */
  public function scanTx(array $options = ['force' => FALSE]): void {
    $summary = $this->snapshotManager->scan((bool) $options['force']);
    $this->io()->writeln(json_encode($summary, JSON_PRETTY_PRINT));
  }

  /**
   * Lists normalized releases.
   *
   * @command nass-release:list
   * @option scope Scope: all, today, upcoming, calendar.
   * @option days Days for upcoming/calendar scopes.
   */
  public function listReleases(array $options = ['scope' => 'all', 'days' => 7]): void {
    $scope = (string) $options['scope'];
    $days = (int) $options['days'];
    $records = match ($scope) {
      'today' => $this->repository->today(),
      'upcoming' => $this->repository->upcoming($days),
      'calendar' => $this->repository->calendar($days),
      default => $this->repository->all(),
    };
    $rows = [];
    foreach ($records as $record) {
      $rows[] = [
        $record['release_datetime'] ?? ($record['start'] ?? ''),
        $record['title'] ?? '',
        $record['source_type'] ?? '',
        $record['filename'] ?? '',
      ];
    }
    $this->io()->table(['Date/time', 'Title', 'Source', 'Filename'], $rows);
  }

}
