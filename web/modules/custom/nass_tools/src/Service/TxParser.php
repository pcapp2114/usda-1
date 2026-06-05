<?php

namespace Drupal\nass_tools\Service;

use Psr\Log\LoggerInterface;

final class TxParser {

  public function __construct(
    private readonly ReleaseNormalizer $normalizer,
    private readonly LoggerInterface $logger,
  ) {}

  public function parseFile(string $file): array {
    if (!is_readable($file)) {
      $this->logger->warning('TX file is not readable: @file', ['@file' => $file]);
      return [];
    }
    $records = [];
    $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [];
    foreach ($lines as $line_number => $line) {
      $record = $this->parseLine($line, basename($file), $line_number + 1);
      if ($record) {
        $records[] = $record;
      }
    }
    return $records;
  }

  public function parseLine(string $line, string $source_file = '', int $line_number = 0): ?object {
    $cols = str_getcsv($line, "\t", '"', '\\');
    if (count($cols) < 7) {
      return NULL;
    }

    $year = $this->normalizeYear($cols[0] ?? '');
    $month = str_pad((string) (int) ($cols[1] ?? 0), 2, '0', STR_PAD_LEFT);
    $day = str_pad((string) (int) ($cols[2] ?? 0), 2, '0', STR_PAD_LEFT);
    $time = preg_replace('/\D+/', '', (string) ($cols[3] ?? '0000'));
    $time = str_pad(substr($time, 0, 4), 4, '0', STR_PAD_LEFT);
    $hour = substr($time, 0, 2);
    $minute = substr($time, 2, 2);

    $datetime = sprintf('%04d-%02d-%02d %02d:%02d:00', $year, $month, $day, $hour, $minute);
    if (strtotime($datetime) === FALSE) {
      return NULL;
    }

    $title = trim((string) ($cols[5] ?? ''));
    $filename = trim((string) ($cols[6] ?? ''));
    $url = trim((string) ($cols[8] ?? ''));

    return $this->normalizer->normalize([
      'external_id' => 'tx:' . hash('sha256', implode('|', [$source_file, $line_number, $title, $datetime, $filename, $url])),
      'title' => $title,
      'release_datetime' => $datetime,
      'filename' => $filename,
      'url' => $url,
      'flags' => [
        'quickstats_only' => $this->flag($cols[9] ?? ''),
        'dissemination' => $this->flag($cols[10] ?? ''),
        'static_link' => $this->flag($cols[11] ?? ''),
        'json' => $this->flag($cols[12] ?? ''),
      ],
      'raw' => [
        'source_file' => $source_file,
        'line_number' => $line_number,
        'columns' => $cols,
      ],
    ], 'tx');
  }

  private function normalizeYear(string $value): int {
    $year = (int) preg_replace('/\D+/', '', $value);
    if ($year < 100) {
      return $year >= 70 ? 1900 + $year : 2000 + $year;
    }
    return $year;
  }

  private function flag(string $value): bool {
    return strtoupper(trim($value)) === 'Y';
  }

}
