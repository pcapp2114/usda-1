<?php

namespace Drupal\nass_release\Service;

use Drupal\Core\Config\ConfigFactoryInterface;

final class ReleaseAttachmentBuilder {

  public function __construct(
    private readonly ConfigFactoryInterface $configFactory,
    private readonly ReleaseRepository $repository,
  ) {}

  public function build(): array {
    $config = $this->configFactory->get('nass_release.adminsettings');
    $host = rtrim((string) ($config->get('nass_release_host_url') ?: 'https://release.nass.usda.gov/reports/'), '/') . '/';
    $no_release = $config->get('nass_release_textarea');

    $report_day = $this->repository->currentReportDay();
    $display_date = $report_day ? substr($report_day[0]['release_datetime'], 0, 10) : NULL;

    return [
      'release_host' => $host,
      'releaseData' => array_map(fn(array $r) => $this->legacyReleaseArray($r, $host), $report_day),
      'upcomingData' => $this->repository->upcoming(NULL, $display_date),
      'currentTime' => '/nass-release-current-data',
      'no_release' => is_array($no_release) ? ($no_release['value'] ?? '') : 'No releases are scheduled for today.',
    ];
  }

  private function legacyReleaseArray(array $record, string $host): array {
    $ts = strtotime($record['release_datetime'] ?? '');
    return $record + [
      'host' => $host,
      'time' => $ts ? date('g:i a', $ts) : '',
      'formatted_date' => $ts ? date('M d, Y', $ts) : '',
      'full_datetime' => $ts ? date('Y-m-d H:i:s', $ts) : ($record['release_datetime'] ?? ''),
    ];
  }

}
