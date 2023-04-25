<?php
namespace Drupal\ajax\Controller;
namespace Drupal\nass_quick_stats\Controller;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Component\Serialization\Json;
use Symfony\Component\HttpFoundation\JsonResponse;

class AjaxControllerUUIDDecode extends ControllerBase {

  public function ajaxCallback() {
    
    $uuid = isset($_GET['uuid']) ? $_GET['uuid'] : '';
    $uuid = rawurlencode($uuid);

    $url = 'https://www.nass.usda.gov/qs/grid/grid_data/' . $uuid . '?start=0&count=10';

    // Curl init
    $ch = curl_init();
    // Will return the response, if false it print the response
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // Set the url
    curl_setopt($ch, CURLOPT_URL,$url);
    // Execute
    $result = curl_exec($ch);
    // Closing
    curl_close($ch);
    
    //$result = json_decode($result, true);
    
    $json = json_decode($result, true);
    $results = isset($json['data']) ? $json['data'] : '';
    return new JsonResponse($json);
  }
}