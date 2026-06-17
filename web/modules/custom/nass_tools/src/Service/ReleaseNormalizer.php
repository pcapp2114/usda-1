<?php

namespace Drupal\nass_tools\Service;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\nass_tools\Model\ReleaseRecord;

/**
 * Normalizes source-specific release data into ReleaseRecord objects.
 */
final class ReleaseNormalizer {

  public function __construct(private readonly ConfigFactoryInterface $configFactory) {}

  public function normalize(array $record, string $source_type): ?ReleaseRecord {
    $config = $this->configFactory->get('nass_release.adminsettings');
    $timezone = $record['timezone'] ?? $config->get('timezone') ?? 'America/New_York';
    $title = trim((string) ($record['title'] ?? ''));
    $datetime = (string) ($record['release_datetime'] ?? $record['date'] ?? '');

    if ($title === '' || $datetime === '' || strtotime($datetime) === FALSE) {
      return NULL;
    }

    $filename = trim((string) ($record['filename'] ?? ''));
    $url = trim((string) ($record['url'] ?? ''));
    $id = $record['id'] ?? $record['external_id'] ?? $this->buildId($source_type, $title, $datetime, $filename, $url);

    return new ReleaseRecord(
      (string) $id,
      $title,
      date('Y-m-d H:i:s', strtotime($datetime)),
      $timezone,
      $filename,
      $url,
      $source_type,
      $record['flags'] ?? [],
      $record['raw'] ?? $record,
    );
  }

  private function buildId(string $source_type, string $title, string $datetime, string $filename, string $url): string {
    return hash('sha256', implode('|', [$source_type, $title, $datetime, $filename, $url]));
  }

}
