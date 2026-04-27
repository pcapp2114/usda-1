<?php

namespace Drupal\nass_release\Service;

use Drupal\Core\Config\ConfigFactoryInterface;

final class TxReleaseSource implements ReleaseSourceInterface {

  public function __construct(
    private readonly ConfigFactoryInterface $configFactory,
    private readonly TxSnapshotManager $snapshotManager,
    private readonly TxFileLocator $locator,
    private readonly TxParser $parser,
  ) {}

  public function fetch(array $options = []): array {
    $config = $this->configFactory->get('nass_release.adminsettings');
    $records = [];

    if ($config->get('tx_use_snapshot_cache') !== FALSE) {
      foreach ($this->snapshotManager->getSnapshot() as $item) {
        $records[] = $item;
      }
      if (!$records) {
        $this->snapshotManager->scan(TRUE);
        $records = $this->snapshotManager->getSnapshot();
      }
      return $this->filterArrays($records, $options);
    }

    foreach ($this->locator->getFiles() as $file) {
      foreach ($this->parser->parseFile($file) as $record) {
        $records[] = $record->toArray();
      }
    }
    return $this->filterArrays($records, $options);
  }

  private function filterArrays(array $records, array $options): array {
    $from = !empty($options['from']) ? strtotime($options['from']) : NULL;
    $to = !empty($options['to']) ? strtotime($options['to']) : NULL;
    $filtered = [];
    foreach ($records as $record) {
      $ts = strtotime($record['release_datetime'] ?? '');
      if (!$ts) {
        continue;
      }
      if ($from && $ts < $from) {
        continue;
      }
      if ($to && $ts > $to) {
        continue;
      }
      $filtered[] = $record;
    }
    return $filtered;
  }

}
