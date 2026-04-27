<?php

namespace Drupal\nass_release\Service;

interface ReleaseSourceInterface {
  /**
   * Returns normalized ReleaseRecord objects.
   */
  public function fetch(array $options = []): array;
}
