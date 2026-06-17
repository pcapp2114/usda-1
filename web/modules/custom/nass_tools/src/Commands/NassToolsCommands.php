<?php

namespace Drupal\nass_tools\Commands;

use Drupal\Core\Database\Connection;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drush\Commands\DrushCommands;

class NassToolsCommands extends DrushCommands {

  private const STATE_ALIASES = [
    'AL' => 'Alabama',
    'AK' => 'Alaska',
    'AZ' => 'Arizona',
    'AR' => 'Arkansas',
    'CA' => 'California',
    'CO' => 'Colorado',
    'CT' => 'Connecticut',
    'DE' => 'Delaware',
    'FL' => 'Florida',
    'GA' => 'Georgia',
    'HI' => 'Hawaii',
    'ID' => 'Idaho',
    'IL' => 'Illinois',
    'IN' => 'Indiana',
    'IA' => 'Iowa',
    'KS' => 'Kansas',
    'KY' => 'Kentucky',
    'LA' => 'Louisiana',
    'ME' => 'Maine',
    'MD' => 'Maryland',
    'MA' => 'Massachusetts',
    'MI' => 'Michigan',
    'MN' => 'Minnesota',
    'MS' => 'Mississippi',
    'MO' => 'Missouri',
    'MT' => 'Montana',
    'NE' => 'Nebraska',
    'NV' => 'Nevada',
    'NH' => 'New Hampshire',
    'NJ' => 'New Jersey',
    'NM' => 'New Mexico',
    'NY' => 'New York',
    'NC' => 'North Carolina',
    'ND' => 'North Dakota',
    'OH' => 'Ohio',
    'OK' => 'Oklahoma',
    'OR' => 'Oregon',
    'PA' => 'Pennsylvania',
    'RI' => 'Rhode Island',
    'SC' => 'South Carolina',
    'SD' => 'South Dakota',
    'TN' => 'Tennessee',
    'TX' => 'Texas',
    'UT' => 'Utah',
    'VT' => 'Vermont',
    'VA' => 'Virginia',
    'WA' => 'Washington',
    'WV' => 'West Virginia',
    'WI' => 'Wisconsin',
    'WY' => 'Wyoming',
  ];

  private const DUPLICATE_IGNORE_FIELDS = [
    'nid',
    'uuid',
    'vid',
    'created',
    'changed',
    'revision_timestamp',
    'revision_uid',
    'revision_log',
    'revision_default',
    'revision_translation_affected',
    'path',
    'menu_link',
    'content_translation_source',
    'content_translation_outdated',
  ];

  protected EntityTypeManagerInterface $entityTypeManager;
  protected Connection $database;
  protected $channelLogger;

  public function __construct(
    EntityTypeManagerInterface $entity_type_manager,
    Connection $database,
    LoggerChannelFactoryInterface $logger_factory,
  ) {
    parent::__construct();
    $this->entityTypeManager = $entity_type_manager;
    $this->database = $database;
    $this->channelLogger = $logger_factory->get('nass_tools');
  }

  /**
   * @command nass:ping
   * @aliases nping
   */
  public function ping(): void {
    $message = 'NASS Tools Drush commands are working.';
    $this->logger()->success($message);
    $this->channelLogger->notice($message);
  }

  /**
   * Finds or removes duplicate state release nodes.
   *
   * The command groups release nodes for the requested state by title. For each
   * title with exactly two matching nodes, it keeps the lower node id and only
   * deletes the higher node id when every non-metadata field is identical.
   *
   * Usage:
   *   drush duplicate-cleanup Tennessee show-duplicate
   *   drush duplicate-cleanup Tennessee remove
   *   drush duplicate-cleanup TN show-duplicate
   *   drush duplicate-cleanup 23 show-duplicate
   *
   * @command duplicate-cleanup
   * @param string $state State term name, postal abbreviation, or term id from the states vocabulary.
   * @param string $mode Use show-duplicate for dry run or remove to delete confirmed duplicates.
   * @option content-type Node content type to inspect. Defaults to release.
   * @option vocabulary State taxonomy vocabulary machine name. Defaults to states.
   * @option state-field State entity reference field machine name. Defaults to field_state.
   * @usage drush duplicate-cleanup Tennessee show-duplicate
   *   Show duplicate Tennessee release nodes without deleting anything.
   * @usage drush duplicate-cleanup Tennessee remove
   *   Delete confirmed-identical duplicate Tennessee release nodes.
   * @aliases dup-cleanup,nass:duplicate-cleanup
   */
  public function duplicateCleanup(
    string $state,
    string $mode = 'show-duplicate',
    array $options = [
      'content-type' => 'release',
      'vocabulary' => 'states',
      'state-field' => 'field_state',
    ],
  ): void {
    $mode = strtolower(trim($mode));
    if (!in_array($mode, ['show-duplicate', 'show', 'dry-run', 'dryrun', 'remove'], TRUE)) {
      throw new \InvalidArgumentException('Invalid mode. Use show-duplicate or remove.');
    }

    $content_type = trim((string) ($options['content-type'] ?? 'release')) ?: 'release';
    $vocabulary = trim((string) ($options['vocabulary'] ?? 'states')) ?: 'states';
    $state_field = trim((string) ($options['state-field'] ?? 'field_state')) ?: 'field_state';
    $remove = $mode === 'remove';

    $state_term = $this->resolveStateTerm($state, $vocabulary);
    $state_tid = (int) $state_term->id();
    $state_name = (string) $state_term->label();

    $this->io()->title('NASS duplicate release cleanup');
    $this->io()->writeln(sprintf('State: %s [tid: %d]', $state_name, $state_tid));
    $this->io()->writeln(sprintf('Content type: %s', $content_type));
    $this->io()->writeln(sprintf('State field: %s', $state_field));
    $this->io()->writeln(sprintf('Mode: %s', $remove ? 'REMOVE confirmed duplicates' : 'DRY RUN / show duplicates'));

    $result = $this->findDuplicateReleaseNodes($state_tid, $content_type, $state_field);

    $this->io()->section('Summary');
    $this->io()->writeln('Duplicate title groups found:        ' . count($result['pairs']));
    $this->io()->writeln('Confirmed identical - delete target: ' . count($result['to_delete']));
    $this->io()->writeln('Skipped groups:                      ' . count($result['skipped']));

    if (!empty($result['to_delete'])) {
      $rows = [];
      foreach ($result['to_delete'] as $duplicate) {
        $rows[] = [
          $duplicate['keep'],
          $duplicate['delete'],
          $duplicate['title'],
        ];
      }
      $this->io()->section($remove ? 'Confirmed duplicates queued for deletion' : 'Confirmed duplicates that would be deleted');
      $this->io()->table(['Keep nid', 'Delete nid', 'Title'], $rows);

      $ids = array_map(static fn(array $duplicate): int => (int) $duplicate['delete'], $result['to_delete']);
      $this->io()->writeln('Delete nid list: ' . implode(',', $ids));
    }
    else {
      $this->io()->success('No confirmed-identical duplicate nodes were found for deletion.');
    }

    if (!empty($result['skipped'])) {
      $this->io()->section('Skipped / not deleted');
      foreach ($result['skipped'] as $skipped) {
        $this->io()->writeln('SKIP: ' . $skipped);
      }
    }

    if (!$remove) {
      $this->io()->success('Dry run complete. Nothing was deleted. Run with mode "remove" to delete confirmed duplicates.');
      return;
    }

    if (empty($result['to_delete'])) {
      return;
    }

    $ids = array_map(static fn(array $duplicate): int => (int) $duplicate['delete'], $result['to_delete']);
    $storage = $this->entityTypeManager->getStorage('node');
    $deleted = 0;

    foreach (array_chunk($ids, 50) as $chunk) {
      $nodes = $storage->loadMultiple($chunk);
      if (!$nodes) {
        continue;
      }
      $storage->delete($nodes);
      $deleted += count($nodes);
      $this->io()->writeln(sprintf('Deleted %d / %d...', $deleted, count($ids)));
    }

    $message = sprintf('Deleted %d duplicate %s release node(s) for %s.', $deleted, $content_type, $state_name);
    $this->logger()->success($message);
    $this->channelLogger->notice($message);
  }

  /**
   * Resolves a state taxonomy term by tid or exact case-insensitive name.
   */
  private function resolveStateTerm(string $state, string $vocabulary): EntityInterface {
    $term_storage = $this->entityTypeManager->getStorage('taxonomy_term');
    $state = trim($state);

    if ($state === '') {
      throw new \InvalidArgumentException('State argument is required.');
    }

    $state_lookup = self::STATE_ALIASES[strtoupper($state)] ?? $state;

    if (ctype_digit($state_lookup)) {
      $term = $term_storage->load((int) $state_lookup);
      if ($term && $term->bundle() === $vocabulary) {
        return $term;
      }
      throw new \InvalidArgumentException(sprintf('Could not find state term id %s in vocabulary %s.', $state_lookup, $vocabulary));
    }

    $query = $term_storage->getQuery()
      ->condition('vid', $vocabulary)
      ->condition('name', $state_lookup)
      ->accessCheck(FALSE)
      ->range(0, 2);
    $tids = $query->execute();

    if (!$tids) {
      throw new \InvalidArgumentException(sprintf('Could not find state term "%s" in vocabulary "%s".', $state_lookup, $vocabulary));
    }

    if (count($tids) > 1) {
      throw new \InvalidArgumentException(sprintf('State term "%s" matched more than one term. Use the term id instead.', $state_lookup));
    }

    $term = $term_storage->load((int) reset($tids));
    if (!$term) {
      throw new \RuntimeException(sprintf('State term "%s" resolved but could not be loaded.', $state_lookup));
    }

    return $term;
  }

  /**
   * Finds duplicate release node pairs and confirms whether each pair matches.
   */
  private function findDuplicateReleaseNodes(int $state_tid, string $content_type, string $state_field): array {
    $storage = $this->entityTypeManager->getStorage('node');
    if (!preg_match('/^[a-z0-9_]+$/', $state_field)) {
      throw new \InvalidArgumentException('Invalid state field machine name.');
    }

    $state_table = 'node__' . $state_field;
    $state_column = $state_field . '_target_id';

    if (!$this->database->schema()->tableExists($state_table)) {
      throw new \RuntimeException(sprintf('State field storage table "%s" does not exist.', $state_table));
    }

    $sql = "\n      SELECT n.title AS title, GROUP_CONCAT(DISTINCT n.nid ORDER BY n.nid) AS nids\n      FROM {node_field_data} n\n      INNER JOIN {" . $state_table . "} s\n        ON s.entity_id = n.nid\n        AND s." . $state_column . " = :tid\n        AND s.deleted = 0\n      WHERE n.type = :type\n        AND n.default_langcode = 1\n      GROUP BY n.title\n      HAVING COUNT(DISTINCT n.nid) = 2\n    ";

    $pairs = $this->database->query($sql, [
      ':tid' => $state_tid,
      ':type' => $content_type,
    ])->fetchAll();

    $to_delete = [];
    $skipped = [];

    foreach ($pairs as $pair) {
      $nids = array_map('intval', explode(',', (string) $pair->nids));
      sort($nids, SORT_NUMERIC);
      [$keep, $delete] = $nids;

      $a = $storage->load($keep);
      $b = $storage->load($delete);
      if (!$a || !$b) {
        $skipped[] = sprintf('%d - could not load pair %d/%d', $delete, $keep, $delete);
        continue;
      }

      $diff = $this->diffNodeFields($a, $b);
      if ($diff) {
        $skipped[] = sprintf('%d - diverged from %d on: %s', $delete, $keep, implode(', ', $diff));
        continue;
      }

      $to_delete[] = [
        'keep' => $keep,
        'delete' => $delete,
        'title' => (string) $pair->title,
      ];
    }

    return [
      'pairs' => $pairs,
      'to_delete' => $to_delete,
      'skipped' => $skipped,
    ];
  }

  /**
   * Compares all non-metadata fields on two nodes.
   */
  private function diffNodeFields(EntityInterface $a, EntityInterface $b): array {
    $diff = [];

    foreach ($a->getFields() as $name => $field) {
      if (in_array($name, self::DUPLICATE_IGNORE_FIELDS, TRUE)) {
        continue;
      }

      $value_a = $field->getString();
      $value_b = $b->hasField($name) ? $b->get($name)->getString() : '';
      if ($value_a !== $value_b) {
        $diff[] = $name;
      }
    }

    return $diff;
  }

}
