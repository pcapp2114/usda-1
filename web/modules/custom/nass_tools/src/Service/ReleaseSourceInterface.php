<?php

namespace Drupal\nass_tools\Service;

interface ReleaseSourceInterface {
  /**
   * Returns normalized ReleaseRecord objects.
   */
  public function fetch(array $options = []): array;
}
