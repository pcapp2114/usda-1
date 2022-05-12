<?php

namespace Drupal\elasticsearch_connector\Plugin\search_api\backend;

use Drupal\Core\Config\Config;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Form\FormBuilderInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Link;
use Drupal\Core\Url;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\elasticsearch_connector\ElasticSearch\ClusterManager;
use Drupal\elasticsearch_connector\ElasticSearch\ClientManagerInterface;
use Drupal\elasticsearch_connector\ElasticSearch\Parameters\Factory\IndexFactory;
use Drupal\elasticsearch_connector\ElasticSearch\Parameters\Factory\SearchFactory;
use Drupal\elasticsearch_connector\ElasticSearch\Parameters\Builder\SearchBuilder;
use Drupal\elasticsearch_connector\Event\BuildSearchQueryEvent;
use Drupal\elasticsearch_connector\Utility\Utility;
use Drupal\search_api\Backend\BackendPluginBase;
use Drupal\search_api\IndexInterface;
use Drupal\search_api\Query\QueryInterface;
use Drupal\search_api\Query\ResultSet;
use Drupal\search_api\Query\ResultSetInterface;
use Drupal\search_api\SearchApiException;
use Drupal\search_api_autocomplete\SearchApiAutocompleteSearchInterface;
use Drupal\search_api_autocomplete\SearchInterface;
use Drupal\search_api_autocomplete\Suggestion\SuggestionFactory;
use Elasticsearch\Common\Exceptions\ElasticsearchException;
use Elastica\Exception\ConnectionException;
use Elastica\Exception\ResponseException;
use Elastica\Response;
use Elastica\Search;
use Elastica\Mapping;
use Elastica\ResultSet as ElasticResultSet;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Plugin\PluginFormInterface;
use Drupal\search_api\Plugin\PluginFormTrait;
use Elastica\Bulk;

/**
 * Elasticsearch Search API Backend definition.
 *
 * TODO: Check for dependencies and remove them in order to properly test the
 * code.
 *
 * @SearchApiBackend(
 *   id = "elasticsearch",
 *   label = @Translation("Elasticsearch"),
 *   description = @Translation("Index items using an Elasticsearch server.")
 * )
 */
class SearchApiElasticsearchBackend extends BackendPluginBase implements PluginFormInterface, SearchApiElasticsearchBackendInterface {

  use PluginFormTrait;

  /**
   * Set a large integer to be the size for a "Hard limit" value of "No limit".
   */
  const FACET_NO_LIMIT_SIZE = 10000;

  /**
   * Auto fuzziness setting.
   *
   * Auto fuzziness in Elasticsearch means we don't specify a specific
   * Levenshtein distance, falling back to auto behavior. Fuzziness, including
   * auto fuzziness, is defined in the Elasticsearch documentation here:
   *
   * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/common-options.html#fuzziness
   */
  const FUZZINESS_AUTO = 'auto';

  /**
   * Elasticsearch settings.
   *
   * @var \Drupal\Core\Config\Config
   */
  protected $elasticsearchSettings;

  /**
   * Cluster id.
   *
   * @var int
   */
  protected $clusterId;

  /**
   * Cluster object.
   *
   * @var \Drupal\elasticsearch_connector\Entity\Cluster
   */
  protected $cluster;

  /**
   * Elasticsearch client.
   *
   * @var \Elastica\Client
   */
  protected $client;

  /**
   * Form builder service.
   *
   * @var \Drupal\Core\Form\FormBuilderInterface
   */
  protected $formBuilder;

  /**
   * Module handler service.
   *
   * @var \Drupal\Core\Extension\ModuleHandlerInterface
   */
  protected $moduleHandler;

  /**
   * Client manager service.
   *
   * @var \Drupal\elasticsearch_connector\ElasticSearch\ClientManagerInterface
   */
  protected $clientManager;

  /**
   * The cluster manager service.
   *
   * @var \Drupal\elasticsearch_connector\ClusterManager
   */
  protected $clusterManager;

  /**
   * The entity type manager service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Logger.
   *
   * @var \Psr\Log\LoggerInterface
   */
  protected $logger;

  /**
   * Elasticsearch index factory.
   *
   * @var \Drupal\elasticsearch_connector\ElasticSearch\Parameters\Factory\IndexFactory
   */
  protected $indexFactory;

  /**
   * SearchApiElasticsearchBackend constructor.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param array $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Form\FormBuilderInterface $form_builder
   *   Form builder service.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   Module handler service.
   * @param \Drupal\elasticsearch_connector\ElasticSearch\ClientManagerInterface $client_manager
   *   Client manager service.
   * @param \Drupal\Core\Config\Config $elasticsearch_settings
   *   Elasticsearch settings object.
   * @param \Psr\Log\LoggerInterface $logger
   *   Logger.
   * @param \Drupal\elasticsearch_connector\Elasticsearch\ClusterManager $cluster_manager
   *   The cluster manager service.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager service.
   * @param \Drupal\elasticsearch_connector\ElasticSearch\Parameters\Factory\IndexFactory $indexFactory
   *   Index factory.
   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
   *   Messenger service.
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   * @throws \Drupal\search_api\SearchApiException
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    array $plugin_definition,
    FormBuilderInterface $form_builder,
    ModuleHandlerInterface $module_handler,
    ClientManagerInterface $client_manager,
    Config $elasticsearch_settings,
    LoggerInterface $logger,
    ClusterManager $cluster_manager,
    EntityTypeManagerInterface $entity_type_manager,
    IndexFactory $indexFactory,
    MessengerInterface $messenger
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);

    $this->formBuilder = $form_builder;
    $this->moduleHandler = $module_handler;
    $this->clientManager = $client_manager;
    $this->logger = $logger;
    $this->elasticsearchSettings = $elasticsearch_settings;
    $this->clusterManager = $cluster_manager;
    $this->entityTypeManager = $entity_type_manager;
    $this->indexFactory = $indexFactory;
    $this->setMessenger($messenger);

    if (empty($this->configuration['cluster_settings']['cluster'])) {
      $this->configuration['cluster_settings']['cluster'] = $this->clusterManager->getDefaultCluster();
    }

    $this->cluster = $this->entityTypeManager->getStorage('elasticsearch_cluster')->load(
      $this->configuration['cluster_settings']['cluster']
    );

    if (!isset($this->cluster)) {
      throw new SearchApiException($this->t('Cannot load the Elasticsearch cluster for your index.'));
    }

    $this->client = $this->clientManager->getClient($this->cluster);

  }

  /**
   * {@inheritdoc}
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   * @throws \Drupal\search_api\SearchApiException
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('form_builder'),
      $container->get('module_handler'),
      $container->get('elasticsearch_connector.client_manager'),
      $container->get('config.factory')->get('elasticsearch.settings'),
      $container->get('logger.channel.elasticsearch'),
      $container->get('elasticsearch_connector.cluster_manager'),
      $container->get('entity_type.manager'),
      $container->get('elasticsearch_connector.index_factory'),
      $container->get('messenger')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'cluster_settings' => [
        'cluster' => '',
      ],
      'scheme' => 'http',
      'host' => 'localhost',
      'port' => '9200',
      'path' => '',
      'excerpt' => FALSE,
      'retrieve_data' => FALSE,
      'highlight_data' => FALSE,
      'http_method' => 'AUTO',
      'autocorrect_spell' => TRUE,
      'autocorrect_suggest_words' => TRUE,
      'fuzziness' => self::FUZZINESS_AUTO,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    if (!$this->server->isNew()) {
      $server_link = $this->cluster->getSafeUrl();
      // Editing this server.
      $form['server_description'] = [
        '#type' => 'item',
        '#title' => $this->t('Elasticsearch Cluster'),
        '#description' => Link::fromTextAndUrl($server_link, Url::fromUri($server_link)),
      ];
    }
    $form['cluster_settings'] = [
      '#type' => 'fieldset',
      '#title' => t('Elasticsearch settings'),
    ];

    // We are not displaying disabled clusters.
    $clusters = $this->clusterManager->loadAllClusters(FALSE);
    $options = [];
    foreach ($clusters as $key => $cluster) {
      $options[$key] = $cluster->cluster_id;
    }

    $options[$this->clusterManager->getDefaultCluster()] = t('Default cluster: @name', ['@name' => $this->clusterManager->getDefaultCluster()]);
    $form['cluster_settings']['cluster'] = [
      '#type' => 'select',
      '#title' => t('Cluster'),
      '#required' => TRUE,
      '#options' => $options,
      '#default_value' => $this->configuration['cluster_settings']['cluster'] ? $this->configuration['cluster_settings']['cluster'] : '',
      '#description' => t('Select the cluster you want to handle the connections.'),
    ];

    // @todo Allow AUTO:[low],[high] parameters and values greater than 5.
    $fuzziness_options = [
      '0' => $this->t('- Disabled -'),
      self::FUZZINESS_AUTO => self::FUZZINESS_AUTO,
    ];
    $fuzziness_options += array_combine(range(1, 5), range(1, 5));
    $form['fuzziness'] = [
      '#type' => 'select',
      '#title' => t('Fuzziness'),
      '#options' => $fuzziness_options,
      '#default_value' => $this->configuration['fuzziness'],
      '#description' => $this->t('Some queries and APIs support parameters to allow inexact fuzzy matching, using the fuzziness parameter. See <a href="https://www.elastic.co/guide/en/elasticsearch/reference/current/common-options.html#fuzziness">Fuzziness</a> for more information.'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   *
   * TODO: implement 'search_api_multi',
   * TODO: implement 'search_api_service_extra',
   * TODO: implement 'search_api_spellcheck',
   * TODO: implement 'search_api_data_type_location',
   * TODO: implement 'search_api_data_type_geohash',
   */
  public function getSupportedFeatures() {
    // First, check the features we always support.
    return [
      'search_api_autocomplete',
      'search_api_facets',
      'search_api_facets_operator_or',
      'search_api_grouping',
      'search_api_mlt',
      'search_api_random_sort',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getSupportedDataTypes() {
    $data_types = ['object', 'location'];
    // Allow other modules to intercept and define what types they want to
    // support.
    $this->moduleHandler->alter('elasticsearch_connector_supported_data_types', $data_types);
    return $data_types;
  }

  /**
   * {@inheritdoc}
   */
  public function viewSettings() {
    $info = [];

    $server_link = $this->cluster->getSafeUrl();
    $info[] = [
      'label' => $this->t('Elasticsearch server URI'),
      'info' => Link::fromTextAndUrl($server_link, Url::fromUri($server_link)),
    ];

    if ($this->server->status()) {
      // If the server is enabled, check whether Elasticsearch can be reached.
      $ping = $this->isAvailable();
      if ($ping) {
        $msg = $this->t('The Elasticsearch server could be reached');
      }
      else {
        $msg = $this->t('The Elasticsearch server could not be reached. Further data is therefore unavailable.');
      }
      $info[] = [
        'label' => $this->t('Connection'),
        'info' => $msg,
        'status' => $ping ? 'ok' : 'error',
      ];
    }

    return $info;
  }

  /**
   * Get the configured cluster; if the cluster is blank, use the default.
   *
   * @return string
   *   The name of the configured cluster.
   */
  public function getCluster() {
    return $this->configuration['cluster_settings']['cluster'];
  }

  /**
   * Get the configured fuzziness value.
   *
   * @return string
   *   The configured fuzziness value.
   */
  public function getFuzziness(): string {
    return $this->configuration['fuzziness'];
  }

  /**
   * {@inheritdoc}
   */
  public function addIndex(IndexInterface $index) {
    $params = $this->indexFactory->index($index);
    $elastic_index = $this->client->getIndex($params['index']);
    if (!$this->isAvailable()) {
      return FALSE;
    }

    try {
      // Delete index if already exists:
      if ($elastic_index->exists()) {
        $elastic_index->delete();
      }
      // Adds index:
      $response = $elastic_index->create($this->indexFactory->create($index));

      if (!$response->isOk()) {
        $this->messenger->addMessage($this->t(
          'Failed to create index. Elasticsearch response: @error',
          ['@error' => $response->getErrorMessage()]
        ), 'error');
        return;
      }

      // Adds mapping:
      $response = $this->createMapping($index);
      if (!$response->isOk()) {
        $this->messenger->addMessage($this->t(
          'Failed to create index mapping. Elasticsearch response: @error',
          ['@error' => $response->getErrorMessage()]
        ), 'error');
      }
    }
    catch (ResponseException | ConnectionException $e) {
      $this->logger->error($e->getMessage());
    }
  }

  /**
   * {@inheritdoc}
   */
  public function updateIndex(IndexInterface $index) {
    // Do not update read-only indexes.
    if ($index->isReadOnly()) {
      return FALSE;
    }
    $params = $this->indexFactory->index($index);
    $elastic_index = $this->client->getIndex($params['index']);
    // Reinstall index, then schedule full reindex.
    try {
      if ($elastic_index->exists()) {
        $this->addIndex($index);
        $index->reindex();
      };
    }
    catch (ResponseException | ConnectionException $e) {
      \Drupal::messenger()->addError($e->getMessage());
    }
  }

  /**
   * Create mapping.
   *
   * @param \Drupal\search_api\IndexInterface $index
   *   Index.
   *
   * @return \Elastica\Response
   *   Response object.
   */
  public function createMapping(IndexInterface $index): Response {
    $mappingParams = $this->indexFactory->mapping($index);
    $mapping = new Mapping($mappingParams['body']['properties']);
    if (isset($mappingParams['dynamic_templates']) && !empty($mappingParams['dynamic_templates'])) {
      $mapping->setParam('dynamic_templates', $mappingParams['dynamic_templates']);
    }
    $params = $this->indexFactory->index($index);
    $elasticIndex = $this->client->getIndex($params['index']);
    return $mapping->send($elasticIndex);
  }

  /**
   * {@inheritdoc}
   */
  public function removeIndex($index) {
    /** @var Drupal\search_api\Entity\Index $index_entity */
    $index_entity = $index instanceof IndexInterface
      ? $index
      : $this->entityTypeManager->getStorage('search_api_index')->load($index);

    // Do not remove read-only indexes.
    if ($index_entity && $index_entity->isReadOnly()) {
      return FALSE;
    }
    if (!$this->isAvailable()) {
      return FALSE;
    }

    $params = $this->indexFactory->index($index);
    $elastic_index = $this->client->getIndex($params['index']);

    try {
      if ($elastic_index->exists()) {
        $elastic_index->delete();
      }
    }
    catch (ResponseException | ConnectionException $e) {
      \Drupal::messenger()->addError($e->getMessage());
    }
  }

  /**
   * {@inheritdoc}
   */
  public function indexItems(IndexInterface $index, array $items) {
    if (empty($items)) {
      return [];
    }

    $params = $this->indexFactory->index($index);
    try {
      $el_index = $this->client->getIndex($params['index']);
      $response = $el_index->addDocuments(
         $this->indexFactory->bulkIndex($index, $items)
      );
      // If there were any errors, log them and throw an exception.
      if ($response->hasError()) {
        foreach ($response->getBulkResponses() as $bulkResponse) {
          if ($bulkResponse->hasError()) {
            $this->logger->error($bulkResponse->getError());
          }
        }
        throw new SearchApiException('An error occurred during indexing. Check your watchdog for more information.');
      }
    }
    catch (ResponseException | ConnectionException $e) {
      $this->messenger->addMessage($e->getMessage(), 'error');
    }

    return array_keys($items);
  }

  /**
   * {@inheritdoc}
   */
  public function deleteAllIndexItems(IndexInterface $index, $datasource_id = NULL): void {
    $this->removeIndex($index);
    $this->addIndex($index);
  }

  /**
   * {@inheritdoc}
   */
  public function deleteItems(IndexInterface $index = NULL, array $ids) {
    if (!count($ids)) {
      return;
    }

    $params = $this->indexFactory->index($index);
    try {
      $data = $this->indexFactory->bulkDelete($index, $ids);
      /** @var \Elastica\Bulk\ResponseSet $response */
      $this->client->bulk($data);
      $this->client->getIndex($params['index'])->refresh();
    }
    catch (ResponseException | ConnectionException $e) {
      \Drupal::messenger()->addError($e->getMessage());
    }
  }

  /**
   * Implements SearchApiAutocompleteInterface::getAutocompleteSuggestions().
   *
   * Note that the interface is not directly implemented to avoid a dependency
   * on search_api_autocomplete module.
   *
   * @param \Drupal\search_api\Query\QueryInterface $query
   *   The query interface parameter.
   * @param \Drupal\search_api_autocomplete\SearchInterface $search
   *   The search interface parameter.
   * @param mixed $incomplete_key
   *   The incomplete key parameter.
   * @param string|null $user_input
   *   The keywords input by the user so far.
   *
   * @return array
   *   Returns autocomplete suggestion array.
   */
  public function getAutocompleteSuggestions(QueryInterface $query, SearchInterface $search, $incomplete_key, $user_input) {
    try {
      $fields = $this->getQueryFulltextFields($query);
      if (count($fields) > 1) {
        throw new \LogicException('Elasticsearch requires a single fulltext field for use with autocompletion! Please adjust your configuration.');
      }
      $query->setOption('autocomplete', $incomplete_key);
      $query->setOption('autocomplete_field', reset($fields));

      // Disable facets so it does not collide with auto-completion results.
      $query->setOption('search_api_facets', FALSE);

      $result = $this->search($query);
      $query->postExecute($result);

      // Parse suggestions out of the response.
      $suggestions = [];
      $factory = new SuggestionFactory($user_input);

      $response = $result->getExtraData('elasticsearch_response');
      if (isset($response['aggregations']['autocomplete']['buckets'])) {
        $suffix_start = strlen($incomplete_key);
        $buckets = $response['aggregations']['autocomplete']['buckets'];
        foreach ($buckets as $bucket) {
          $suggestions[] = $factory->createFromSuggestionSuffix(substr($bucket['key'], $suffix_start), $bucket['doc_count']);
        }
      }
      return $suggestions;
    }
    catch (\Exception $e) {
      $this->logger->error($e->getMessage());
      return [];
    }
  }

  /**
   * {@inheritdoc}
   */
  public function search(QueryInterface $query) {

    // Build Elasticsearch query.
    try {

      // Allow modules to alter the Elastic Search query.
      $this->moduleHandler->alter('elasticsearch_connector_search_api_query', $query);
      $this->preQuery($query);

      // Build Elasticsearch query.
      $builder = new SearchBuilder($query);
      $builder->build();
      $elastic_query = $builder->getElasticQuery();

      // Allow other modules to alter search query before we use it.
      $this->moduleHandler->alter('elasticsearch_connector_elastic_search_query', $elastic_query, $query);
      $dispatcher = \Drupal::service('event_dispatcher');
      $prepareSearchQueryEvent = new BuildSearchQueryEvent($elastic_query, $query, $query->getIndex());
      $event = $dispatcher->dispatch(BuildSearchQueryEvent::BUILD_QUERY, $prepareSearchQueryEvent);
      $elastic_query = $event->getElasticQuery();

      // Execute search.
      $params = $this->indexFactory->index($query->getIndex());
      $search = new Search($this->client);
      $search->addIndex($params['index']);
      $result_set = $search->search($elastic_query);
      $results = self::parseResult($query, $result_set);
      self::parseSpellingSuggestions($query, $result_set);
      self::parseFacets($query, $result_set);

      // Allow modules to alter the Elastic Search Results.
      $this->moduleHandler->alter('elasticsearch_connector_search_results', $results, $query, $result_set);
      $this->postQuery($results, $query, $result_set);

      return $results;
    }
    catch (ResponseException | ConnectionException $e) {
      /** @var \Elastica\Response */
      $response = $e->getResponse();
      $transferInfo = $response->getTransferInfo();
      $this->messenger->addMessage($this->t(
        'Failed to make search call to @url.<br />
        Elasticsearch response: @error',
        ['@error' => $e->getMessage(), '@url' => $transferInfo['url']]
      ), 'error');
      $this->logger->error($e->getMessage());
      return $query->getResults();
    }
  }

  /**
   * Parse a Elasticsearch response into a ResultSetInterface.
   *
   * @param \Drupal\search_api\Query\QueryInterface $query
   *   Search API query.
   * @param \Elastica\ResultSet $result_set
   *   ResultSet.
   *
   * @return \Drupal\search_api\Query\ResultSetInterface
   *   The results of the search.
   */
  public static function parseResult(QueryInterface $query, ElasticResultSet $result_set): ResultSetInterface {
    $index = $query->getIndex();
    $fields = $index->getFields();

    // Set up the results array.
    $results = $query->getResults();
    $results->setExtraData('elasticsearch_response', $result_set);
    $results->setResultCount($result_set->getTotalHits());

    /** @var \Drupal\search_api\Utility\FieldsHelper $fields_helper */
    $fields_helper = \Drupal::getContainer()->get('search_api.fields_helper');

    foreach ($result_set->getResults() as $result) {
      $result = $result->getHit();
      $result_item = $fields_helper->createItem($index, $result['_id']);
      $result_item->setScore($result['_score']);

      // Nested objects needs to be unwrapped before passing into fields.
      $flatten_result = Utility::dot($result['_source'], '', '__');
      foreach ($flatten_result as $result_key => $result_value) {
        if (isset($fields[$result_key])) {
          $field = clone $fields[$result_key];
        }
        else {
          $field = $fields_helper->createField($index, $result_key);
        }
        $field->setValues((array) $result_value);
        $result_item->setField($result_key, $field);
      }

      // Preserve complex fields defined in index as unwrapped.
      foreach ($result['_source'] as $result_key => $result_value) {
        if (
          isset($fields[$result_key]) &&
          in_array($fields[$result_key]->getType(), [
            'object',
            'nested_object',
          ])
        ) {
          $field = clone $fields[$result_key];
          $field->setValues((array) $result_value);
          $field->extraData['value'] = (array) $result_value;
          $result_item->setField($result_key, $field);
        }
      }

      $results->addResultItem($result_item);
    }

    return $results;
  }

  /**
   * Parse spelling suggestions from result set.
   *
   * @param \Drupal\search_api\Query\QueryInterface $query
   *   Search API query.
   * @param \Elastica\ResultSet $result_set
   *   ResultSet.
   */
  public static function parseSpellingSuggestions(QueryInterface $query, ElasticResultSet $result_set) {
    $suggestions = [];
    if (isset($result_set->getSuggests()['spelling_suggestion'])) {
      $spelling_suggestions = $result_set->getSuggests()['spelling_suggestion'];
      foreach ($spelling_suggestions as $spelling_suggestion) {
        foreach ($spelling_suggestion['options'] as $phrase) {
          $suggestions[] = $phrase['text'];
        }
      }
    }

    $results = $query->getResults();
    $results->setExtraData('search_api_spelling_suggestions', $suggestions);
  }

  /**
   * {@inheritdoc}
   */
  public function isAvailable() {
    if (!$this->client->hasConnection()) {
      return FALSE;
    }

    try {
      $this->client->getVersion();
      return TRUE;
    }
    catch (ResponseException | ConnectionException $e) {
      $this->logger->error($e->getMessage());
      $this->messenger->addMessage($e->getMessage(), 'error');
      return FALSE;
    }
  }

  /**
   * Parse the result set and add the facet values.
   *
   * @param \Drupal\search_api\Query\QueryInterface $query
   *   Search API query.
   * @param \Elastica\ResultSet $result_set
   *   ResultSet.
   */
  public static function parseFacets(QueryInterface $query, ElasticResultSet $result_set) {
    if (!$result_set->hasAggregations()) {
      return;
    }

    $facets = $query->getOption('search_api_facets', []);
    $search_api_facets = [];
    $aggregations = $result_set->getAggregations();

    foreach ($facets as $facet_id => $facet) {
      if (!isset($aggregations[$facet_id])) {
        continue;
      }

      $terms = [];

      // Buckets may be nested.
      $buckets = [];
      $agg = $aggregations[$facet_id];
      while ($agg !== NULL) {
        if ($facet['query_type'] === 'search_api_range') {
          if (isset($agg['min']['value']) || isset($agg['max']['value'])) {
            $buckets[] = [
              'doc_count' => $aggregations[$facet_id]['doc_count'],
              'key'       => $agg['min']['value'],
            ];
            $buckets[] = [
              'doc_count' => $aggregations[$facet_id]['doc_count'],
              'key'       => $agg['max']['value'],
            ];
            break;
          }
        }
        elseif (isset($agg['buckets'])) {
          $buckets = $agg['buckets'];
          break;
        }

        if (isset($agg[$facet_id])) {
          $agg = $agg[$facet_id];
          continue;
        }

        $agg = NULL;
      }

      array_walk($buckets, static function ($value) use (&$terms, $facet) {
        if ($value['doc_count'] >= $facet['min_count']) {
          $terms[] = [
            'count'  => $value['doc_count'],
            'filter' => $value['key'] !== '' ? '"' . $value['key'] . '"' : '!',
          ];
        }
      });

      $search_api_facets[$facet_id] = $terms;
    }
  }

  /**
   * Prefixes an index ID as configured.
   *
   * The resulting ID will be a concatenation of the following strings:
   * - If set, the "elasticsearch.settings.index_prefix" configuration.
   * - If set, the index-specific "elasticsearch.settings.index_prefix_INDEX"
   *   configuration.
   * - The index's machine name.
   *
   * @param string $machine_name
   *   The index's machine name.
   *
   * @return string
   *   The prefixed machine name.
   */
  protected function getIndexId($machine_name) {
    // Prepend per-index prefix.
    $id = $this->elasticsearchSettings->get('index_prefix_' . $machine_name) . $machine_name;
    // Prepend environment prefix.
    $id = $this->elasticsearchSettings->get('index_prefix') . $id;
    return $id;
  }

  /**
   * Helper function. Return date gap from two dates or timestamps.
   *
   * @param mixed $min
   *   Start date or timestamp.
   * @param mixed $max
   *   End date or timestamp.
   * @param bool $timestamp
   *   TRUE if the first two params are timestamps, FALSE otherwise. In the case
   *   of FALSE, it's assumed the first two arguments are strings and they are
   *   converted to timestamps using strtotime().
   *
   * @return string
   *   One of 'NONE', 'YEAR', 'MONTH', or 'DAY' depending on the difference
   *
   * @see facetapi_get_timestamp_gap()
   *
   * @deprecated in elasticsearch_connector:8.x-7.5 and is removed from elasticsearch_connector:8.0.0.
   *   This was a helper function from removed facet implementation.
   * @see https://www.drupal.org/project/elasticsearch_connector/issues/3186932
   */
  protected static function getDateGap($min, $max, $timestamp = TRUE) {
    if ($timestamp !== TRUE) {
      $min = strtotime($min);
      $max = strtotime($max);
    }

    if (empty($min) || empty($max)) {
      return 'DAY';
    }

    $diff = $max - $min;

    switch (TRUE) {
      case ($diff > 86400 * 365):
        return 'NONE';

      case ($diff > 86400 * gmdate('t', $min)):
        return 'YEAR';

      case ($diff > 86400):
        return 'MONTH';

      default:
        return 'DAY';
    }
  }

  /**
   * Helper function to return date gap.
   *
   * @param $adapter
   *   The adapter parameter.
   * @param mixed $facet_id
   *   The facet it parameter.
   *
   * @return mixed|string
   *   Returns date gap.
   *
   * @deprecated in elasticsearch_connector:8.x-7.5 and is removed from elasticsearch_connector:8.0.0.
   *   This was a helper function from removed facet implementation.
   * @see https://www.drupal.org/project/elasticsearch_connector/issues/3186932
   */
  public function getDateGranularity($adapter, $facet_id) {
    // Date gaps.
    $gap_weight = ['YEAR' => 2, 'MONTH' => 1, 'DAY' => 0];
    $gaps = [];
    $date_gap = 'YEAR';

    // Get the date granularity.
    if (isset($adapter)) {
      // Get the current date gap from the active date filters.
      $active_items = $adapter->getActiveItems(['name' => $facet_id]);
      if (!empty($active_items)) {
        foreach ($active_items as $active_item) {
          $value = $active_item['value'];
          if (strpos($value, ' TO ') > 0) {
            [$date_min, $date_max] = explode(
              ' TO ',
              str_replace(['[', ']'], '', $value),
              2);
            $gap = self::getDateGap($date_min, $date_max, FALSE);
            if (isset($gap_weight[$gap])) {
              $gaps[] = $gap_weight[$gap];
            }
          }
        }
        if (!empty($gaps)) {
          // Minimum gap.
          $date_gap = array_search(min($gaps), $gap_weight);
        }
      }
    }

    return $date_gap;
  }

  /**
   * Allow custom changes before sending a search query to Elasticsearch.
   *
   * This allows subclasses to apply custom changes before the query is sent to
   * Elasticsearch.
   *
   * @param \Drupal\search_api\Query\QueryInterface $query
   *   The \Drupal\search_api\Query\Query object representing the executed
   *   search query.
   */
  protected function preQuery(QueryInterface $query) {
  }

  /**
   * Allow custom changes before search results are returned for subclasses.
   *
   * @param \Drupal\search_api\Query\ResultSetInterface $results
   *   The results array that will be returned for the search.
   * @param \Drupal\search_api\Query\QueryInterface $query
   *   The \Drupal\search_api\Query\Query object representing the executed
   *   search query.
   * @param object $response
   *   The response object returned by Elasticsearch.
   */
  protected function postQuery(ResultSetInterface $results, QueryInterface $query, $response) {
  }

  /* TODO: Implement the settings update feature. */

  /**
   * {@inheritdoc}
   */
  public function supportsDataType($type) {
    $data_types = $this->getSupportedDataTypes();
    return in_array($type, $data_types);
  }

  /**
   * Implements __sleep()
   *
   * Prevents closure serialization error on search_api server add form.
   */
  public function __sleep() {
    return [];
  }

}
