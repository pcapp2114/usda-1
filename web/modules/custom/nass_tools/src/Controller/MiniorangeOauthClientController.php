<?php

namespace Drupal\nass_tools\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Compatibility controller for the legacy /signin-oidc route.
 *
 * The uploaded legacy nass_eauth_login module declared this route but did not
 * include the referenced MiniOrange controller class. This class prevents a
 * fatal route callback error while keeping the route path and method name in
 * place for existing integrations that may post back to /signin-oidc.
 */
final class MiniorangeOauthClientController extends ControllerBase {

  /**
   * Handles the legacy MiniOrange OAuth callback route.
   *
   * @return \Symfony\Component\HttpFoundation\RedirectResponse
   *   A safe redirect to the front page when no external controller is present.
   */
  public function miniorange_oauth_client_mo_login(): RedirectResponse {
    return new RedirectResponse('/');
  }

}
