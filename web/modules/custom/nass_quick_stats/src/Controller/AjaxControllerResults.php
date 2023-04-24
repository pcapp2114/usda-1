<?php
namespace Drupal\ajax\Controller;
namespace Drupal\nass_quick_stats\Controller;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Component\Serialization\Json;
use Symfony\Component\HttpFoundation\JsonResponse;

class AjaxControllerResults extends ControllerBase {

  public function ajaxCallback() {
    
    $sector = isset($_GET['sector']) ? $_GET['sector'] : '';
    $group = isset($_GET['group']) ? $_GET['group'] : '';
    $commodity = isset($_GET['commodity']) ? $_GET['commodity'] : '';

    $sector = rawurlencode($sector);
    $group = rawurlencode($group);
    $commodity = rawurlencode($commodity);

    $config = \Drupal::config('nass_quick_stats.adminsettings');
    $variables['nass_quick_stats_url'] = $config->get('nass_quick_stats_url'); 
    $variables['nass_quick_stats_key'] = $config->get('nass_quick_stats_key'); 

    // construct the query with our apikey and the query we want to make
    //$url = $variables['nass_quick_stats_url'] . '/api/get_counts/?key=' . $variables['nass_quick_stats_key'] . '&commodity_desc=CORN&year__GE=2012&state_alpha=VA';
    //https://www.nass.usda.gov/qs/get_constraints/short_desc/asc?source_desc=SURVEY&sector_desc=ECONOMICS&group_desc=EXPENSES&commodity_desc=FEED&format=JSON'
    //$url = $variables['nass_quick_stats_url'] . '/api/api_GET/?key=' . $variables['nass_quick_stats_key'] . '&source_desc=SURVEY&sector_desc=CROPS&group_desc=VEGETABLES&commodity_desc=BEETS&format=JSON';
    $url = $variables['nass_quick_stats_url'] . '/api/get_param_values/?key=' . $variables['nass_quick_stats_key'] . '&param=short_desc&source_desc=SURVEY&sector_desc=' . $sector . '&group_desc=' . $group . '&commodity_desc=' . $commodity . '&format=JSON';

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