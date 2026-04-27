<?php

namespace Drupal\nass_release\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Datetime\DrupalDateTime;
use Symfony\Component\HttpFoundation\JsonResponse;

final class AjaxTodaysController extends ControllerBase {

  public function ajaxCallback(): JsonResponse {
    $date_original = new DrupalDateTime('now', 'America/New_York');
    $current_time = \Drupal::service('date.formatter')->format($date_original->getTimestamp(), 'custom', 'Y-m-d H:i:s');
    return new JsonResponse($current_time);
  }

}
