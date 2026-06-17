<?php
namespace Drupal\ajax\Controller;
namespace Drupal\nass_tools\Controller;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Component\Serialization\Json;
use Symfony\Component\HttpFoundation\JsonResponse;

class AjaxControllerSector extends ControllerBase {

  public function ajaxCallback() {

    $config = \Drupal::config('nass_quick_stats.adminsettings');
    $variables['nass_quick_stats_url'] = $config->get('nass_quick_stats_url'); 
    $variables['nass_quick_stats_key'] = $config->get('nass_quick_stats_key'); 

    // construct the query with our apikey and the query we want to make
    //$url = $variables['nass_quick_stats_url'] . '/api/get_counts/?key=' . $variables['nass_quick_stats_key'] . '&commodity_desc=CORN&year__GE=2012&state_alpha=VA';
    //$url = $variables['nass_quick_stats_url'] . '/api/api_GET/?key=' . $variables['nass_quick_stats_key'] . '&commodity_desc=CORN&year__GE=2012&state_alpha=VA&format=JSON';
    $url = $variables['nass_quick_stats_url'] . '/api/get_param_values/?key=' . $variables['nass_quick_stats_key'] . '&param=sector_desc&format=JSON&reference_period_desc=YEAR&agg_level_desc=NATIONAL&source_desc=SURVEY&freq_desc=ANNUAL';
    
    //print_r($url);
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