<?php

namespace Drupal\nass_release\Service;

use Drupal\Core\Entity\EntityTypeManagerInterface;

final class NodeReleaseSource implements ReleaseSourceInterface {

  public function __construct(
    private readonly EntityTypeManagerInterface $entityTypeManager,
    private readonly ReleaseNormalizer $normalizer,
  ) {}

  public function fetch(array $options = []): array {
    if (!$this->entityTypeManager->hasDefinition('node')) {
      return [];
    }
    $storage = $this->entityTypeManager->getStorage('node');
    $query = $storage->getQuery()->accessCheck(TRUE)->condition('type', 'national_release');
    if (!empty($options['from'])) {
      $query->condition('field_release_date_time.value', date('Y-m-d\TH:i:s', strtotime($options['from'])), '>=');
    }
    if (!empty($options['to'])) {
      $query->condition('field_release_date_time.value', date('Y-m-d\TH:i:s', strtotime($options['to'])), '<=');
    }
    $query->sort('field_release_date_time.value', 'ASC');
    $nids = $query->execute();
    if (!$nids) {
      return [];
    }

    $records = [];
    foreach ($storage->loadMultiple($nids) as $node) {
      if (!$node->hasField('field_release_date_time') || $node->get('field_release_date_time')->isEmpty()) {
        continue;
      }
      $datetime = $node->get('field_release_date_time')->value;
      $filename = $node->hasField('field_release_filename') && !$node->get('field_release_filename')->isEmpty() ? $node->get('field_release_filename')->value : '';
      $url = $node->hasField('field_release_url') && !$node->get('field_release_url')->isEmpty() ? $node->get('field_release_url')->value : '';
      $record = $this->normalizer->normalize([
        'id' => 'node:' . $node->id(),
        'title' => $node->label(),
        'release_datetime' => $datetime,
        'filename' => $filename,
        'url' => is_array($url) ? '' : $url,
        'raw' => ['nid' => $node->id()],
      ], 'node');
      if ($record) {
        $records[] = $record;
      }
    }
    return $records;
  }

}
