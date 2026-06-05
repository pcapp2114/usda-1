<?php

namespace Drupal\nass_tools\Service;

use Drupal\Core\Config\ConfigFactoryInterface;

final class ReleaseRepository {

  public function __construct(
    private readonly ConfigFactoryInterface $configFactory,
    private readonly NodeReleaseSource $nodeSource,
    private readonly TxReleaseSource $txSource,
    private readonly ApiReleaseSource $apiSource,
  ) {}

  public function all(array $options = []): array {
    $enabled = $this->enabledSources();
    $priority = $this->sourcePriority();
    $sources = [
      'node' => $this->nodeSource,
      'tx' => $this->txSource,
      'api' => $this->apiSource,
    ];

    $records = [];
    foreach ($priority as $source_id) {
      if (empty($enabled[$source_id]) || empty($sources[$source_id])) {
        continue;
      }
      foreach ($sources[$source_id]->fetch($options) as $record) {
        $array = is_object($record) && method_exists($record, 'toArray') ? $record->toArray() : $record;
        if (empty($array['release_datetime']) || empty($array['title'])) {
          continue;
        }
        $key = $this->dedupeKey($array);
        if (!isset($records[$key])) {
          $records[$key] = $array;
        }
      }
    }

    usort($records, static fn(array $a, array $b) => strcmp($a['release_datetime'], $b['release_datetime']));
    return array_values($records);
  }

  public function today(?\DateTimeImmutable $date = NULL): array {
    $tz = new \DateTimeZone($this->timezone());
    $start = $date ? $date->setTimezone($tz)->setTime(0, 0) : new \DateTimeImmutable('today', $tz);
    $end = $start->modify('+1 day -1 second');
    return $this->all(['from' => $start->format('Y-m-d H:i:s'), 'to' => $end->format('Y-m-d H:i:s')]);
  }

  /**
   * Returns the report group shown in the legacy "Today's Reports" widget.
   *
   * NASS treats the next scheduled release day as the current report day. For
   * example, on a Sunday evening the widget must show Monday's pending reports
   * instead of repeating "No releases are scheduled for today."
   */
  public function currentReportDay(): array {
    $tz = new \DateTimeZone($this->timezone());
    $now = new \DateTimeImmutable('now', $tz);

    $today = $this->today($now);
    if ($today) {
      return $today;
    }

    $future = $this->all([
      'from' => $now->setTime(0, 0)->format('Y-m-d H:i:s'),
      'to' => $now->modify('+370 days')->setTime(23, 59, 59)->format('Y-m-d H:i:s'),
    ]);
    if (!$future) {
      return [];
    }

    $next_day = substr($future[0]['release_datetime'], 0, 10);
    return array_values(array_filter($future, static fn(array $record): bool => substr($record['release_datetime'], 0, 10) === $next_day));
  }

  public function upcoming(?int $days = NULL, ?string $after_date = NULL): array {
    $config = $this->configFactory->get('nass_release.adminsettings');
    $days = $days ?? (int) ($config->get('upcoming_days') ?: 7);
    $tz = new \DateTimeZone($this->timezone());

    if ($after_date) {
      $start = (new \DateTimeImmutable($after_date, $tz))->modify('+1 day')->setTime(0, 0);
    }
    else {
      $start = new \DateTimeImmutable('tomorrow', $tz);
    }

    $end = $start->modify('+' . max(1, $days) . ' days -1 second');
    return $this->all(['from' => $start->format('Y-m-d H:i:s'), 'to' => $end->format('Y-m-d H:i:s')]);
  }

  /**
   * Returns the next N upcoming release records after a display date.
   *
   * This is used by the "Upcoming Releases" panel. It intentionally does
   * not stop at the configured upcoming_days window because the next scheduled
   * reports may be more than 7 days away.
   */
  public function nextUpcoming(int $limit = 3, ?string $after_date = NULL): array {
    $tz = new \DateTimeZone($this->timezone());

    if ($after_date) {
      $start = (new \DateTimeImmutable($after_date, $tz))->modify('+1 day')->setTime(0, 0);
    }
    else {
      $start = new \DateTimeImmutable('tomorrow', $tz);
    }

    $items = $this->all([
      'from' => $start->format('Y-m-d H:i:s'),
      'to' => $start->modify('+370 days')->setTime(23, 59, 59)->format('Y-m-d H:i:s'),
    ]);

    return array_slice($items, 0, max(1, $limit));
  }

  public function calendar(?int $days = NULL): array {
    $items = $this->all([
      'from' => (new \DateTimeImmutable('-370 days', new \DateTimeZone($this->timezone())))->format('Y-m-d H:i:s'),
      'to' => (new \DateTimeImmutable('+370 days', new \DateTimeZone($this->timezone())))->format('Y-m-d H:i:s'),
    ]);
    return array_map(static function (array $record): array {
      return [
        'id' => $record['id'] ?? '',
        'title' => $record['title'] ?? '',
        'start' => date('c', strtotime($record['release_datetime'])),
        'url' => $record['url'] ?: NULL,
        'extendedProps' => $record,
      ];
    }, $items);
  }

  public function enabledSources(): array {
    $configured = $this->configFactory->get('nass_release.adminsettings')->get('release_sources');
    if (!is_array($configured)) {
      return ['tx' => TRUE, 'api' => FALSE, 'node' => TRUE];
    }
    return [
      'tx' => array_key_exists('tx', $configured) ? (bool) $configured['tx'] : TRUE,
      'api' => array_key_exists('api', $configured) ? (bool) $configured['api'] : FALSE,
      'node' => array_key_exists('node', $configured) ? (bool) $configured['node'] : TRUE,
    ];
  }

  private function sourcePriority(): array {
    $priority = $this->configFactory->get('nass_release.adminsettings')->get('source_priority');
    if (!is_array($priority) || !$priority) {
      return ['tx', 'api', 'node'];
    }
    return array_values(array_unique(array_merge($priority, ['tx', 'api', 'node'])));
  }

  private function timezone(): string {
    return $this->configFactory->get('nass_release.adminsettings')->get('timezone') ?: 'America/New_York';
  }

  private function dedupeKey(array $record): string {
    $filename = $record['filename'] ?? '';
    if ($filename !== '') {
      return 'file:' . strtolower($filename) . ':' . date('YmdHi', strtotime($record['release_datetime']));
    }
    return strtolower(($record['title'] ?? '') . ':' . date('YmdHi', strtotime($record['release_datetime'] ?? 'now')));
  }

}
