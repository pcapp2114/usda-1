<?php

namespace Drupal\nass_release\Service;

use Drupal\Core\State\StateInterface;
use Psr\Log\LoggerInterface;

final class TxSnapshotManager {
  private const MANIFEST_KEY = 'nass_release.tx_manifest';
  private const SNAPSHOT_KEY = 'nass_release.tx_snapshot';

  public function __construct(
    private readonly StateInterface $state,
    private readonly TxFileLocator $locator,
    private readonly TxParser $parser,
    private readonly LoggerInterface $logger,
  ) {}

  public function scan(bool $force_rebuild = FALSE): array {
    $old_manifest = $this->state->get(self::MANIFEST_KEY, []);
    $new_manifest = $this->locator->getManifest();
    $changed = $force_rebuild || $old_manifest != $new_manifest;

    $summary = [
      'changed' => $changed,
      'directory' => $this->locator->getActiveDirectory(),
      'file_count' => count($new_manifest),
      'new_files' => array_values(array_diff(array_keys($new_manifest), array_keys($old_manifest))),
      'removed_files' => array_values(array_diff(array_keys($old_manifest), array_keys($new_manifest))),
      'updated_files' => [],
      'record_count' => count($this->getSnapshot()),
    ];

    foreach ($new_manifest as $file => $meta) {
      if (isset($old_manifest[$file]) && $old_manifest[$file] !== $meta) {
        $summary['updated_files'][] = $file;
      }
    }

    if ($changed) {
      $records = [];
      foreach (array_keys($new_manifest) as $file) {
        foreach ($this->parser->parseFile($file) as $record) {
          $records[] = $record->toArray();
        }
      }
      usort($records, static fn(array $a, array $b) => strcmp($a['release_datetime'], $b['release_datetime']));
      $this->state->set(self::MANIFEST_KEY, $new_manifest);
      $this->state->set(self::SNAPSHOT_KEY, $records);
      $summary['record_count'] = count($records);
      $this->logger->notice('TX release snapshot rebuilt with @count records from @files files.', ['@count' => count($records), '@files' => count($new_manifest)]);
    }

    return $summary;
  }

  public function getSnapshot(): array {
    return $this->state->get(self::SNAPSHOT_KEY, []);
  }

  public function clear(): void {
    $this->state->delete(self::MANIFEST_KEY);
    $this->state->delete(self::SNAPSHOT_KEY);
  }

}
