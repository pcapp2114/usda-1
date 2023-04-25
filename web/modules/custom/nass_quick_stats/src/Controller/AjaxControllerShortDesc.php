<?php
namespace Drupal\ajax\Controller;
namespace Drupal\nass_quick_stats\Controller;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Component\Serialization\Json;
use Symfony\Component\HttpFoundation\JsonResponse;

class AjaxControllerShortDesc extends ControllerBase {

  public function ajaxCallback() {
    
    $url_shortdesc = isset($_GET['url']) ? $_GET['url'] : '';
    $shortdesc = isset($_GET['short_desc']) ? $_GET['short_desc'] : '';
    $sector = isset($_GET['sector_desc']) ? $_GET['sector_desc'] : '';
    $group = isset($_GET['group_desc']) ? $_GET['group_desc'] : '';
    $commodity = isset($_GET['commodity_desc']) ? $_GET['commodity_desc'] : '';

    $sector = rawurlencode($sector);
    $group = rawurlencode($group);
    $commodity = rawurlencode($commodity);
    $url_shortdesc = rawurlencode($url_shortdesc);
    
    $url_shortdesc_trimmed = str_replace($remove, '', $url_shortdesc);

    //print_r($shortdesc);
    //print_r($url_shortdesc_trimmed);

    $config = \Drupal::config('nass_quick_stats.adminsettings');

    $url = 'https://www.nass.usda.gov/qs/uuid/encode?sector_desc=' . $sector . '&group_desc=' . $group . '&commodity_desc=' . $commodity. '&' . $url_shortdesc_trimmed . 'reference_period_desc=YEAR&agg_level_desc=NATIONAL&source_desc=SURVEY&freq_desc=ANNUAL';

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