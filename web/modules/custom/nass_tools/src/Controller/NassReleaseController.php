<?php

namespace Drupal\nass_tools\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Request;

final class NassReleaseController extends ControllerBase {

  public function content(): array {
    return \Drupal::service('nass_release.release_render_builder')->buildTodayBlock();
  }

  public function calendar(Request $request): array {
    return \Drupal::service('nass_release.release_render_builder')->buildCalendarPage($request->query->get('month'));
  }

}
