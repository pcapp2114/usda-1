<?php

namespace Drupal\nass_tools\Service;

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

    $tz = new \DateTimeZone($config->get('timezone') ?: 'America/New_York');
    $today = new \DateTimeImmutable('now', $tz);
    $today_date = $today->format('Y-m-d');
    $report_day = $this->repository->today($today);
    $upcoming = $this->repository->nextUpcoming(3, $today_date);

    return [
      'release_host' => $host,
      'releaseData' => array_map(fn(array $r) => $this->legacyReleaseArray($r, $host), $report_day),
      // Homepage Upcoming Releases must be the next three after today.
      // Do not include today's releases.
      'upcomingData' => array_map(fn(array $r) => $this->legacyReleaseArray($r, $host), $upcoming),
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
