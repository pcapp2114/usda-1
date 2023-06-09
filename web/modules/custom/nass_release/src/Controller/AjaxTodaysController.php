<?php
namespace Drupal\ajax\Controller;
namespace Drupal\nass_release\Controller;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Component\Serialization\Json;
use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\Core\Datetime\DrupalDateTime;

class AjaxTodaysController extends ControllerBase {

  public function ajaxCallback() {
    
    $date_original= new DrupalDateTime('now','America/New_York');     
    $current_time = \Drupal::service('date.formatter')->format( $date_original->getTimestamp(), 'custom', 'Y-m-d H:i:s'  ); 

    return new JsonResponse($current_time);

  }
}