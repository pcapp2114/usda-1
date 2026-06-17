<?php

namespace Drupal\nass_tools\Commands;

use Drupal\nass_tools\Service\ReleaseNodeSync;
use Drupal\nass_tools\Service\ReleaseRepository;
use Drupal\nass_tools\Service\TxSnapshotManager;
use Drush\Commands\DrushCommands;

final class NassReleaseCommands extends DrushCommands {

  public function __construct(
    private readonly TxSnapshotManager $snapshotManager,
    private readonly ReleaseRepository $repository,
    private readonly ReleaseNodeSync $nodeSync,
  ) {
    parent::__construct();
  }

  /**
   * Scans TX files and rebuilds the TX snapshot when files changed.
   *
   * @command nass-release:scan-tx
   * @option force Force snapshot rebuild even if files did not change. Defaults to TRUE for this project workflow.
   * @option sync-nodes Sync TX records into national_release nodes for the legacy calendar View. Defaults to TRUE.
   * @option clear-cache Clear Drupal caches after scanning and syncing. Defaults to TRUE.
   * @option no-sync-nodes Skip syncing TX records into national_release nodes.
   * @option no-clear-cache Skip automatic Drupal cache rebuild.
   * @aliases nass-releases:scan-tx
   */
  public function scanTx(array $options = [
    'force' => TRUE,
    'sync-nodes' => TRUE,
    'clear-cache' => TRUE,
    'no-sync-nodes' => FALSE,
    'no-clear-cache' => FALSE,
  ]): void {
    $force = !array_key_exists('force', $options) || (bool) $options['force'];
    $sync_nodes = empty($options['no-sync-nodes']) && (!array_key_exists('sync-nodes', $options) || (bool) $options['sync-nodes']);
    $clear_cache = empty($options['no-clear-cache']) && (!array_key_exists('clear-cache', $options) || (bool) $options['clear-cache']);

    $this->io()->writeln($force ? 'Scanning TX files with force rebuild enabled...' : 'Scanning TX files...');
    $summary = $this->snapshotManager->scan($force);
    $this->io()->writeln(json_encode($summary, JSON_PRETTY_PRINT));

    if ($sync_nodes) {
      $this->io()->writeln('Syncing TX releases into national_release nodes...');
      $this->io()->writeln(json_encode($this->nodeSync->sync(['update_existing' => TRUE]), JSON_PRETTY_PRINT));
    }

    if ($clear_cache) {
      $this->clearDrupalCaches();
      $this->io()->success('Drupal caches rebuilt.');
    }
  }


  /**
   * Syncs TX release records into national_release nodes for the calendar View.
   *
   * @command nass-release:sync-nodes
   * @option from Optional lower-bound datetime, for example 2026-01-01 00:00:00.
   * @option to Optional upper-bound datetime, for example 2026-12-31 23:59:59.
   * @option no-update Do not update matching existing national_release nodes.
   */
  public function syncNodes(array $options = ['from' => NULL, 'to' => NULL, 'no-update' => FALSE]): void {
    $sync_options = [
      'update_existing' => empty($options['no-update']),
    ];
    if (!empty($options['from'])) {
      $sync_options['from'] = (string) $options['from'];
    }
    if (!empty($options['to'])) {
      $sync_options['to'] = (string) $options['to'];
    }

    $summary = $this->nodeSync->sync($sync_options);
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


  /**
   * Clears Drupal caches after release snapshot/node updates.
   */
  private function clearDrupalCaches(): void {
    if (function_exists('drupal_flush_all_caches')) {
      drupal_flush_all_caches();
      return;
    }

    try {
      \Drupal::service('cache_tags.invalidator')->invalidateTags([
        'config:nass_release.adminsettings',
        'node_list',
        'rendered',
      ]);
    }
    catch (\Throwable) {
      // Do not fail the scan if cache invalidation is unavailable in the
      // current Drush bootstrap phase.
    }
  }

}
