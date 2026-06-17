<?php

namespace Drupal\nass_tools\Service;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Component\Datetime\TimeInterface;
use Psr\Log\LoggerInterface;

/**
 * Builds reusable render arrays for NASS release displays.
 */
final class ReleaseRenderBuilder {

  public function __construct(
    private readonly ConfigFactoryInterface $configFactory,
    private readonly ReleaseRepository $repository,
    private readonly TxSnapshotManager $snapshotManager,
    private readonly TimeInterface $time,
    private readonly LoggerInterface $logger,
  ) {}

  public function buildTodayBlock(): array {
    $config = $this->configFactory->get('nass_release.adminsettings');
    $timezone = $config->get('timezone') ?: 'America/New_York';
    $tz = new \DateTimeZone($timezone);
    $upcoming_limit = 3;
    $host = rtrim((string) ($config->get('nass_release_host_url') ?: 'https://release.nass.usda.gov/reports/'), '/') . '/';

    $this->ensureSnapshot();

    try {
      $now = new \DateTimeImmutable('now', $tz);
      $display_date = $now->format('Y-m-d');
      $today_releases = $this->repository->today($now);
      // Upcoming Releases must be the next three after today, not today's releases.
      $upcoming_releases = $this->repository->nextUpcoming($upcoming_limit, $display_date);
    }
    catch (\Throwable $e) {
      $this->logger->error('Unable to load NASS releases for render: @message', ['@message' => $e->getMessage()]);
      $today_releases = [];
      $upcoming_releases = [];
      $display_date = (new \DateTimeImmutable('now', $tz))->format('Y-m-d');
    }

    $textarea = $config->get('nass_release_textarea');
    $no_release_message = is_array($textarea) && !empty($textarea['value'])
      ? $textarea['value']
      : 'For the complete release schedule, please visit the <a href="/data-and-statistics/calendar/release-calendar">Calendar</a>.';

    return [
      '#theme' => 'nass_release_todays_release',
      '#enabled' => (int) ($config->get('nass_release_on_off_toggle') ?? 1) === 1,
      '#disabled_message' => $config->get('nass_release_disabled_message') ?: 'No releases are scheduled for today.',
      '#today_date' => strtoupper((new \DateTimeImmutable($display_date, $tz))->format('M d, Y')),
      '#today_releases' => $this->prepareReleases($today_releases, $host, $timezone),
      '#upcoming_releases' => $this->prepareReleases($upcoming_releases, $host, $timezone),
      '#no_release_message' => $no_release_message,
      '#upcoming_note' => 'For the complete release schedule, please visit the <a href="/data-and-statistics/calendar/release-calendar">Calendar</a>.',
      '#calendar_url' => '/data-and-statistics/calendar/release-calendar',
      '#attached' => [
        'library' => ['nass_tools/nass_release'],
      ],
      '#cache' => [
        'max-age' => 0,
        'contexts' => ['timezone', 'url.path'],
        'tags' => ['config:nass_release.adminsettings'],
      ],
    ];
  }

  public function buildCalendarPage(?string $month = NULL): array {
    $this->ensureSnapshot();

    $tz = new \DateTimeZone($this->configFactory->get('nass_release.adminsettings')->get('timezone') ?: 'America/New_York');
    try {
      $month_start = $month ? new \DateTimeImmutable($month . '-01', $tz) : new \DateTimeImmutable('first day of this month', $tz);
    }
    catch (\Throwable) {
      $month_start = new \DateTimeImmutable('first day of this month', $tz);
    }
    $month_start = $month_start->setTime(0, 0);
    $month_end = $month_start->modify('last day of this month')->setTime(23, 59, 59);

    $records = $this->repository->all([
      'from' => $month_start->format('Y-m-d H:i:s'),
      'to' => $month_end->format('Y-m-d H:i:s'),
    ]);

    $events_by_date = [];
    foreach ($records as $record) {
      $date_key = substr($record['release_datetime'], 0, 10);
      $events_by_date[$date_key][] = $record;
    }

    return [
      '#theme' => 'nass_release_calendar_page',
      '#events_by_date' => $events_by_date,
      '#month_label' => $month_start->format('F Y'),
      '#month' => $month_start->format('Y-m'),
      '#previous_month_url' => '/data-and-statistics/calendar/release-calendar?month=' . $month_start->modify('-1 month')->format('Y-m'),
      '#next_month_url' => '/data-and-statistics/calendar/release-calendar?month=' . $month_start->modify('+1 month')->format('Y-m'),
      '#calendar_days' => $this->calendarDays($month_start),
      '#attached' => ['library' => ['nass_tools/nass_release']],
      '#cache' => ['max-age' => 0],
    ];
  }

  private function calendarDays(\DateTimeImmutable $month_start): array {
    $cursor = $month_start->modify('monday this week');
    if ($cursor > $month_start) {
      $cursor = $cursor->modify('-1 week');
    }

    $days = [];
    while (count($days) < 35) {
      if ((int) $cursor->format('N') <= 5) {
        $days[] = [
          'date' => $cursor->format('Y-m-d'),
          'day' => $cursor->format('j'),
          'in_month' => $cursor->format('m') === $month_start->format('m'),
        ];
      }
      $cursor = $cursor->modify('+1 day');
    }
    return $days;
  }

  private function ensureSnapshot(): void {
    try {
      if (!$this->snapshotManager->getSnapshot()) {
        $this->snapshotManager->scan(TRUE);
      }
    }
    catch (\Throwable $e) {
      $this->logger->warning('Unable to initialize TX snapshot for release render: @message', ['@message' => $e->getMessage()]);
    }
  }

  private function prepareReleases(array $records, string $host, string $timezone): array {
    $items = [];
    $now = $this->time->getRequestTime();
    $tz = new \DateTimeZone($timezone);

    foreach ($records as $record) {
      $datetime = (string) ($record['release_datetime'] ?? $record['date'] ?? '');
      if ($datetime === '') {
        continue;
      }

      try {
        $date = new \DateTimeImmutable($datetime, $tz);
      }
      catch (\Throwable) {
        continue;
      }

      $timestamp = $date->getTimestamp();
      $filename = trim((string) ($record['filename'] ?? ''));
      $url = trim((string) ($record['url'] ?? ''));
      $released = $timestamp <= $now;

      $links = [];
      if ($released && $filename !== '') {
        $links = [
          'text' => $host . $filename . '.txt',
          'pdf' => $host . $filename . '.pdf',
          'csv' => $host . $filename . '.zip',
        ];
      }
      elseif ($released && $url !== '') {
        $links = ['report' => $url];
      }

      $items[] = [
        'id' => $record['id'] ?? '',
        'title' => $record['title'] ?? '',
        'release_datetime' => $datetime,
        'timestamp' => $timestamp,
        'formatted_date' => $date->format('M d, Y'),
        'time' => $date->format('g:i a'),
        'filename' => $filename,
        'url' => $url,
        'released' => $released,
        'links' => $links,
      ];
    }

    return $items;
  }

}
