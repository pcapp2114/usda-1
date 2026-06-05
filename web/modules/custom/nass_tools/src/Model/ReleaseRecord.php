<?php

namespace Drupal\nass_tools\Model;

/**
 * Value object for one normalized NASS release record.
 */
final class ReleaseRecord {

  public function __construct(
    public readonly string $id,
    public readonly string $title,
    public readonly string $releaseDateTime,
    public readonly string $timezone = 'America/New_York',
    public readonly string $filename = '',
    public readonly string $url = '',
    public readonly string $sourceType = 'unknown',
    public readonly array $flags = [],
    public readonly array $raw = [],
  ) {}

  public function toArray(): array {
    $timestamp = strtotime($this->releaseDateTime);
    return [
      'id' => $this->id,
      'title' => $this->title,
      'date' => $this->releaseDateTime,
      'release_datetime' => $this->releaseDateTime,
      'full_datetime' => date('Y-m-d H:i:s', $timestamp),
      'formatted_date' => date('M d, Y', $timestamp),
      'time' => date('g:i a', $timestamp),
      'filename' => $this->filename,
      'url' => $this->url,
      'source_type' => $this->sourceType,
      'flags' => $this->flags,
      'raw' => $this->raw,
    ];
  }

}
