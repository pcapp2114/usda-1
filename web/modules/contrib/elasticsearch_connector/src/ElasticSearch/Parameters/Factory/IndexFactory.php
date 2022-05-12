<?php

namespace Drupal\elasticsearch_connector\ElasticSearch\Parameters\Factory;

use Drupal\elasticsearch_connector\Event\PrepareDocumentIndexEvent;
use Drupal\search_api\IndexInterface;
use Drupal\elasticsearch_connector\Event\PrepareIndexEvent;
use Drupal\elasticsearch_connector\Event\PrepareIndexMappingEvent;
use Drupal\search_api_autocomplete\Suggester\SuggesterInterface;
use Drupal\elasticsearch_connector\Entity\Cluster;
use Elastica\Document;

/**
 * Create Elasticsearch Indices.
 */
class IndexFactory {

  /**
   * Build parameters required to index.
   *
   * @todo: We need to handle the following params as well:
   * ['consistency'] = (enum) Explicit write consistency setting for the
   * operation
   * ['refresh']     = (boolean) Refresh the index after performing the
   * operation
   * ['replication'] = (enum) Explicitly set the replication type
   * ['fields']      = (list) Default comma-separated list of fields to return
   * in the response for updates.
   *
   * @param \Drupal\search_api\IndexInterface $index
   *   Index to create.
   *
   * @return array
   *   Associative array with the following keys:
   *   - index: The name of the index on the Elasticsearch server.
   */
  public static function index(IndexInterface $index) {
    $params = [];
    $params['index'] = static::getIndexName($index);
    return $params;
  }

  /**
   * Build parameters required to create an index.
   *
   * @param \Drupal\search_api\IndexInterface $index
   *   Index to create.
   *
   * @return array
   *   Index configuration.
   */
  public static function create(IndexInterface $index) {
    $indexName = static::getIndexName($index);
    $index->getOptions();

    // @todo: Add the timeout option.
    $indexConfig = [
      'settings' => [
        'index' => [
          'number_of_shards'   => $index->getOption('number_of_shards', 5),
          'number_of_replicas' => $index->getOption('number_of_replicas', 2),
        ],
      ],
    ];

    // Allow other modules to alter index config before we create it.
    $dispatcher = \Drupal::service('event_dispatcher');
    $prepareIndexEvent = new PrepareIndexEvent($indexConfig, $index);
    $event = $dispatcher->dispatch(PrepareIndexEvent::PREPARE_INDEX, $prepareIndexEvent);
    $indexConfig = $event->getIndexConfig();

    return $indexConfig;
  }

  /**
   * Build parameters to bulk delete indexes.
   *
   * @param \Drupal\search_api\IndexInterface $index
   *   Index object.
   * @param array $ids
   *   Search API index ids.
   *
   * @return array
   *   Bulk API body array.
   */
  public static function bulkDelete(IndexInterface $index, array $ids) {
    $indexName = static::index($index);

    $params = [];
    foreach ($ids as $id) {
      $params[]['delete'] = [
        '_index' => $indexName['index'],
        '_id' => $id,
      ];
    }

    return $params;
  }

  /**
   * Build parameters to bulk delete indexes.
   *
   * @param \Drupal\search_api\IndexInterface $index
   *   Index object.
   * @param \Drupal\search_api\Item\ItemInterface[] $items
   *   An array of items to be indexed, keyed by their item IDs.
   *
   * @return array
   *   Array of parameters to send along to Elasticsearch to perform the bulk
   *   index.
   */
  public static function bulkIndex(IndexInterface $index, array $items) {
    $dispatcher = \Drupal::service('event_dispatcher');
    $documents = [];

    foreach ($items as $id => $item) {
      $data = [
        '_language' => $item->getLanguage(),
      ];
      /** @var \Drupal\search_api\Item\FieldInterface $field */
      foreach ($item as $name => $field) {
        $field_type = $field->getType();
        $values = [];
        if (!empty($field->getValues())) {
          foreach ($field->getValues() as $value) {
            $values[] = self::getFieldValue($field_type, $value);
          }
          $data[$field->getFieldIdentifier()] = $values;
        }
      }
      // Allow other modules to alter document before we create it.
      $documentIndexEvent = new PrepareDocumentIndexEvent(
        $data,
        $index
      );

      /** @var \Drupal\elasticsearch_connector\Event\PrepareDocumentIndexEvent $event */
      $event = $dispatcher->dispatch(
        PrepareDocumentIndexEvent::PREPARE_DOCUMENT_INDEX,
        $documentIndexEvent
      );

      $documents[] = new Document($id, $event->getDocument());
    }

    return $documents;
  }

  /**
   * Build parameters required to create an index mapping.
   *
   * TODO: We need also:
   * $params['index'] - (Required)
   * ['type'] - The name of the document type
   * ['timeout'] - (time) Explicit operation timeout.
   *
   * @param \Drupal\search_api\IndexInterface $index
   *   Index object.
   *
   * @return array
   *   Parameters required to create an index mapping.
   */
  public static function mapping(IndexInterface $index) {
    $params = static::index($index);

    $properties = [
      'id' => [
        'type' => 'keyword',
        'index' => 'true',
      ],
    ];

    // Figure out which fields are used for autocompletion if any.
    if (\Drupal::moduleHandler()->moduleExists('search_api_autocomplete')) {
      $autocompletes = \Drupal::entityTypeManager()->getStorage('search_api_autocomplete_search')->loadMultiple();
      $all_autocompletion_fields = [];
      foreach ($autocompletes as $autocomplete) {
        $suggester = \Drupal::service('plugin.manager.search_api_autocomplete.suggester');
        $plugin = $suggester->createInstance('server', ['#search' => $autocomplete]);
        assert($plugin instanceof SuggesterInterface);
        $configuration = $plugin->getConfiguration();
        $autocompletion_fields = isset($configuration['fields']) ? $configuration['fields'] : [];
        if (!$autocompletion_fields) {
          $autocompletion_fields = $plugin->getSearch()->getIndex()->getFulltextFields();
        }

        // Collect autocompletion fields in an array keyed by field id.
        $all_autocompletion_fields += array_flip($autocompletion_fields);
      }
    }

    // Map index fields.
    foreach ($index->getFields() as $field_id => $field_data) {
      $properties[$field_id] = MappingFactory::mappingFromField($field_data);
      // Enable fielddata for fields that are used with autocompletion.
      if (isset($all_autocompletion_fields[$field_id])) {
        $properties[$field_id]['fielddata'] = TRUE;
      }
    }

    $properties['_language'] = [
      'type' => 'keyword',
    ];

    $params['body']['properties'] = $properties;

    // Allow other modules to alter index mapping before we create it.
    $dispatcher = \Drupal::service('event_dispatcher');
    $prepareIndexMappingEvent = new PrepareIndexMappingEvent($params, $params['index']);
    $event = $dispatcher->dispatch(PrepareIndexMappingEvent::PREPARE_INDEX_MAPPING, $prepareIndexMappingEvent);
    $params = $event->getIndexMappingParams();

    return $params;
  }

  /**
   * Helper function. Returns the Elasticsearch name of an index.
   *
   * @param \Drupal\search_api\IndexInterface $index
   *   Index object.
   *
   * @return string
   *   The name of the index on the Elasticsearch server. Includes a prefix for
   *   uniqueness, the database name, and index machine name.
   */
  public static function getIndexName(IndexInterface $index) {
    // Get index machine name.
    $index_machine_name = is_string($index) ? $index : $index->id();
    /** @var \Drupal\elasticsearch_connector\Plugin\search_api\backend\SearchApiElasticsearchBackend $backend */
    $backend = $index->getServerInstance()->getBackend();

    // Get prefix and suffix from the cluster if present.
    $cluster_id = $backend->getCluster();

    $cluster_options = Cluster::load($cluster_id)->options;

    $index_suffix = '';
    if (!empty($cluster_options['rewrite']['rewrite_index'])) {
      $index_prefix = isset($cluster_options['rewrite']['index']['prefix']) ? $cluster_options['rewrite']['index']['prefix'] : '';
      if ($index_prefix && substr($index_prefix, -1) !== '_') {
        $index_prefix .= '_';
      }
      $index_suffix = isset($cluster_options['rewrite']['index']['suffix']) ? $cluster_options['rewrite']['index']['suffix'] : '';
      if ($index_suffix && $index_suffix[0] !== '_') {
        $index_suffix = '_' . $index_suffix;
      }
    }
    else {
      // If a custom rewrite is not enabled, set prefix to db name by default.
      $options = \Drupal::database()->getConnectionOptions();
      $index_prefix = 'elasticsearch_index_' . $options['database'] . '_';
    }

    return strtolower(preg_replace(
      '/[^A-Za-z0-9_]+/',
      '',
      $index_prefix . $index_machine_name . $index_suffix
    ));
  }

  /**
   * Helper function. Returns the elasticsearch value for a given field.
   *
   * @param string $field_type
   *   Field data type.
   * @param mixed $raw
   *   Field value.
   *
   * @return mixed
   *   Field value optionally casted to specific type.
   */
  protected static function getFieldValue($field_type, $raw) {
    $value = $raw;

    switch ($field_type) {
      case 'string':
        if (!is_array($raw)) {
          $value = (string) $raw;
        }
        break;

      case 'text':
        $value = $raw->toText();
        break;

      case 'boolean':
        $value = (boolean) $raw;
        break;

      case 'integer':
        $value = (integer) $raw;
        break;

      case 'decimal':
        $value = (float) $raw;
        break;
    }

    return $value;
  }

}
