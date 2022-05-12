<?php

namespace Drupal\elasticsearch_connector\ElasticSearch;

use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Logger\LoggerChannelInterface;
use Drupal\elasticsearch_connector\Entity\Cluster;
use Elastica\Client;
use Elastica\Exception\ConnectionException;

/**
 * Class ClientManager.
 * //class ClientManager implements ClientManagerInterface {
 */
class ClientManager implements ClientManagerInterface {

  /**
   * Array of clients keyed by cluster URL.
   *
   * @var \Elastica\Client[]
   */
  protected $clients = [];

  /**
   * Module handler service.
   *
   * @var \Drupal\Core\Extension\ModuleHandlerInterface
   */
  protected $moduleHandler;

  /**
   * Logger.
   *
   * @var \Drupal\Core\Logger\LoggerChannelInterface|null
   */
  private $logger;

  /**
   * ClientManager constructor.
   *
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $moduleHandler
   *   Module service.
   * @param \Drupal\Core\Logger\LoggerChannelInterface|null $logger
   *   Logger.
   */
  public function __construct(ModuleHandlerInterface $moduleHandler, LoggerChannelInterface $logger = NULL) {
    //$this->clientManagerFactory = $clientManagerFactory;
    $this->moduleHandler = $moduleHandler;
    $this->logger        = $logger;
  }

  /**
   * Returns Elasticsearch client.
   *
   * @param \Drupal\elasticsearch_connector\Entity\Cluster $cluster
   *   Cluster to connect.
   *
   * @return \Elastica\Client
   *   Instance of Elasticsearch client.
   */
  public function getClient(Cluster $cluster): Client {

    $url = rtrim($cluster->url, '/') . '/';
    if (!isset($this->clients[$url])) {
      $timeout = !empty($cluster->options['timeout']) ?
        (int) $cluster->options['timeout'] :
        Cluster::ELASTICSEARCH_CONNECTOR_DEFAULT_TIMEOUT;

      $options = [
        'url'       => $url,
        // @todo Setting transport fails when using Basic auth.
        // 'transport' => 'Guzzle',
        'timeout'   => $timeout,
      ];
      if ($cluster->options['use_authentication']) {
        $options['username'] = $cluster->options['username'];
        $options['password'] = $cluster->options['password'];
        $options['auth_type'] = $cluster->options['authentication_type'];
      }
      $this->moduleHandler->alter(
        'elasticsearch_connector_load_library_options',
        $options,
        $cluster
      );

      // Skip logger until severity is properly filtered.
      // $this->clients[$url] = new Client($options, NULL, $this->logger);
      $this->clients[$url] = new Client($options, NULL);

    }
    return $this->clients[$url];
  }

}
