<?php

namespace Drupal\nass_release\Controller;
use Drupal\Core\Link;

/**
 * Controller for nass release
 */
class Controller {

  public function content() {

    $content = 'Test';
  
    return array(
      '#markup' => $content,
    );
  }
}