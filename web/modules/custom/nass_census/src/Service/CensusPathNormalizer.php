<?php

namespace Drupal\nass_census\Service;

/**
 * Normalizes Census folder names, filenames, and human labels.
 */
final class CensusPathNormalizer {

  /**
   * Converts a label or path fragment into a safe lowercase slug.
   */
  public function slug(string $value, string $separator = '_'): string {
    $value = html_entity_decode($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $value = trim($value);
    $value = str_replace(['&', '+'], ' and ', $value);
    $value = preg_replace('/[^A-Za-z0-9]+/', $separator, $value) ?? '';
    $value = strtolower(trim($value, $separator));
    $value = preg_replace('/' . preg_quote($separator, '/') . '+/', $separator, $value) ?? $value;
    return $value !== '' ? $value : 'item';
  }

  /**
   * Normalizes a URL path directory, preserving path hierarchy.
   */
  public function normalizeDirectoryPath(string $relative_path): string {
    $relative_path = trim(rawurldecode($relative_path), '/');
    if ($relative_path === '') {
      return '';
    }
    $parts = array_filter(explode('/', $relative_path), static fn($part) => $part !== '');
    $parts = array_map(fn($part) => $this->slug($part), $parts);
    return implode('/', $parts);
  }

  /**
   * Builds a safe filename from a title and original URL/file extension.
   */
  public function normalizeFilename(string $title, string $source_url, array &$used_names = []): string {
    $path = parse_url($source_url, PHP_URL_PATH) ?: '';
    $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
    $extension = $extension !== '' ? $extension : 'bin';

    $base = $this->slug($title);

    // Make table numbers sortable and predictable.
    $base = preg_replace_callback('/^table_([0-9]{1,3})(_|$)/', static function (array $matches): string {
      return 'table_' . str_pad($matches[1], 3, '0', STR_PAD_LEFT) . $matches[2];
    }, $base) ?? $base;

    // Keep file names reasonably portable.
    if (strlen($base) > 140) {
      $base = rtrim(substr($base, 0, 140), '_');
    }

    $candidate = $base . '.' . $extension;
    $i = 2;
    while (isset($used_names[$candidate])) {
      $candidate = $base . '_' . $i . '.' . $extension;
      $i++;
    }
    $used_names[$candidate] = TRUE;
    return $candidate;
  }


  /**
   * Normalizes multiple path fragments into a safe relative path.
   *
   * @param array<int, string> $parts
   *   Human-readable path parts.
   */
  public function normalizePathParts(array $parts): string {
    $normalized = [];
    foreach ($parts as $part) {
      $slug = $this->slug((string) $part);
      if ($slug !== '' && $slug !== 'item') {
        $normalized[] = $slug;
      }
    }
    return implode('/', $normalized);
  }

  /**
   * Turns a URL/path fragment into a readable fallback label.
   */
  public function readableLabel(string $value): string {
    $path = parse_url($value, PHP_URL_PATH) ?: $value;
    $name = pathinfo(rawurldecode($path), PATHINFO_FILENAME);
    $name = preg_replace('/[_\-]+/', ' ', $name) ?? $name;
    $name = preg_replace('/\s+/', ' ', $name) ?? $name;
    return trim($name) !== '' ? trim($name) : 'Untitled file';
  }

  /**
   * Normalizes a label captured from HTML.
   */
  public function cleanLabel(string $label): string {
    $label = html_entity_decode($label, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $label = preg_replace('/\s+/', ' ', $label) ?? $label;
    $label = trim($label);
    return $label !== '' ? $label : 'Untitled file';
  }

}
