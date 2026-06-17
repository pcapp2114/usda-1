<?php

namespace Drupal\nass_tools\Service;

use Drupal\Core\Entity\EntityFieldManagerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Logger\LoggerChannelInterface;

/**
 * Synchronizes normalized release records into national_release nodes.
 *
 * The legacy release calendar page is a Drupal View over national_release
 * nodes. The TX/repository data powers the homepage block directly, but the
 * calendar view will remain empty until the same records exist as nodes. This
 * service bridges that gap without changing the existing View/calendar module.
 */
final class ReleaseNodeSync {

  public function __construct(
    private readonly EntityTypeManagerInterface $entityTypeManager,
    private readonly EntityFieldManagerInterface $entityFieldManager,
    private readonly TxReleaseSource $txSource,
    private readonly LoggerChannelInterface $logger,
  ) {}

  /**
   * Syncs TX release records into national_release nodes.
   *
   * @param array $options
   *   Supported options:
   *   - from: Optional datetime lower bound.
   *   - to: Optional datetime upper bound.
   *   - update_existing: Whether existing nodes should be updated.
   *
   * @return array
   *   Summary counts.
   */
  public function sync(array $options = []): array {
    $summary = [
      'processed' => 0,
      'created' => 0,
      'updated' => 0,
      'skipped' => 0,
      'errors' => 0,
      'message' => '',
    ];

    if (!$this->hasNationalReleaseBundle()) {
      $summary['message'] = 'The national_release content type does not exist. Release calendar node sync was skipped.';
      return $summary;
    }

    $fields = $this->getNationalReleaseFields();
    if (empty($fields['field_release_date_time'])) {
      $summary['message'] = 'The field_release_date_time field does not exist on national_release. Release calendar node sync was skipped.';
      return $summary;
    }

    $update_existing = (bool) ($options['update_existing'] ?? TRUE);
    $records = $this->txSource->fetch($options);
    $storage = $this->entityTypeManager->getStorage('node');

    foreach ($records as $record) {
      $summary['processed']++;

      try {
        $title = trim((string) ($record['title'] ?? ''));
        $datetime = trim((string) ($record['release_datetime'] ?? $record['date'] ?? ''));

        if ($title === '' || $datetime === '' || strtotime($datetime) === FALSE) {
          $summary['skipped']++;
          continue;
        }

        $field_datetime = $this->formatDrupalDateTime($datetime);
        $filename = trim((string) ($record['filename'] ?? ''));
        $url = trim((string) ($record['url'] ?? ''));
        if ($url === '' && $filename !== '') {
          $url = $this->buildReportUrl($filename);
        }

        $nid = $this->findExistingNodeId($title, $field_datetime, $filename, $fields);

        if ($nid) {
          if (!$update_existing) {
            $summary['skipped']++;
            continue;
          }
          $node = $storage->load($nid);
          if (!$node) {
            $summary['skipped']++;
            continue;
          }
        }
        else {
          $node = $storage->create([
            'type' => 'national_release',
            'title' => $title,
            'status' => 1,
            'promote' => 0,
          ]);
        }

        $node->setTitle($title);
        $node->set('field_release_date_time', $field_datetime);

        if (!empty($fields['field_release_filename'])) {
          $node->set('field_release_filename', $filename);
        }

        if (!empty($fields['field_quick_stats_release'])) {
          $quickstats = !empty($record['flags']['quickstats_only']) ? 'Y' : 'N';
          $node->set('field_quick_stats_release', $quickstats);
        }

        if (!empty($fields['field_release_url']) && $url !== '') {
          $node->set('field_release_url', [
            'uri' => $url,
            'title' => '',
          ]);
        }

        $node->save();

        if ($nid) {
          $summary['updated']++;
        }
        else {
          $summary['created']++;
        }
      }
      catch (\Throwable $e) {
        $summary['errors']++;
        $this->logger->error('Unable to sync release node @title: @message', [
          '@title' => (string) ($record['title'] ?? ''),
          '@message' => $e->getMessage(),
        ]);
      }
    }

    $summary['message'] = 'Release calendar node sync completed.';
    return $summary;
  }

  private function hasNationalReleaseBundle(): bool {
    try {
      return (bool) $this->entityTypeManager->getStorage('node_type')->load('national_release');
    }
    catch (\Throwable) {
      return FALSE;
    }
  }

  private function getNationalReleaseFields(): array {
    try {
      return $this->entityFieldManager->getFieldDefinitions('node', 'national_release');
    }
    catch (\Throwable) {
      return [];
    }
  }

  private function findExistingNodeId(string $title, string $field_datetime, string $filename, array $fields): ?int {
    $storage = $this->entityTypeManager->getStorage('node');

    if ($filename !== '' && !empty($fields['field_release_filename'])) {
      $query = $storage->getQuery()
        ->accessCheck(FALSE)
        ->condition('type', 'national_release')
        ->condition('field_release_filename.value', $filename)
        ->range(0, 1);
      $nids = $query->execute();
      if ($nids) {
        return (int) reset($nids);
      }
    }

    $query = $storage->getQuery()
      ->accessCheck(FALSE)
      ->condition('type', 'national_release')
      ->condition('title', $title)
      ->condition('field_release_date_time.value', $field_datetime)
      ->range(0, 1);
    $nids = $query->execute();

    return $nids ? (int) reset($nids) : NULL;
  }

  private function formatDrupalDateTime(string $datetime): string {
    return date('Y-m-d\TH:i:s', strtotime($datetime));
  }

  private function buildReportUrl(string $filename): string {
    return 'https://release.nass.usda.gov/reports/' . rawurlencode($filename) . '.txt';
  }

}
