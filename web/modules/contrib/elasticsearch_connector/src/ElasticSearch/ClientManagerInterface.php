<?php

namespace Drupal\elasticsearch_connector\ElasticSearch;

use Drupal\Core\Extension\ModuleHandlerInterface;
// TODO: Cluster should be an interface!
use Drupal\elasticsearch_connector\Entity\Cluster;

/**
 * Client manager interface.
 */
interface ClientManagerInterface {

  /**
   * Returns Elasticsearch client.
   *
   * @param \Drupal\elasticsearch_connector\Entity\Cluster $cluster
   *   Cluster to connect.
   *
   * @return \Elastica\Client
   *   Instance of Elasticsearch client.
   */
  public function getClient(Cluster $cluster);

}
