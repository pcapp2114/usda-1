<?php

namespace Drupal\oauth_login_oauth2;

/**
 * Handles constants used throughout the project.
 */
class MiniorangeOAuthClientConstants {

  const BASE_URL = 'https://login.xecurify.com';
  const SUPPORT_EMAIL = 'drupalsupport@xecurify.com';
  const FEEDBACK_URL = 'https://login.xecurify.com/moas/api/notify/send';
  const AUTH_CODE_GRANT = "Authorization Code Grant";
  const AUTH_CODE_PKCE_GRANT = "Authorization Code with PKCE";
  const PASS_GRANT = "Password Grant";
  const IMPLICIT_GRANT = "Implicit Grant";
  const AUTH_CODE_GRANT_GUIDE = "https://www.drupal.org/docs/contributed-modules/drupal-oauth-openid-connect-login-oauth2-client-sso-login/what-is-oauth-20-authorization-code-grant";
  const AUTH_CODE_PKCE_GRANT_GUIDE = "https://www.drupal.org/docs/contributed-modules/drupal-oauth-openid-connect-login-oauth2-client-sso-login/what-is-oauth-20-authorization-code-grant";
  const PASS_GRANT_GUIDE = "https://www.drupal.org/docs/contributed-modules/drupal-oauth-openid-connect-login-oauth2-client-sso-login/what-is-oauth-20-password-grant";
  const IMPLICIT_GRANT_GUIDE = "https://www.drupal.org/docs/contributed-modules/drupal-oauth-openid-connect-login-oauth2-client-sso-login/what-is-oauth-20-implicit-grant";
  const COMMON_SCOPES = ['openid', 'profile', 'email', 'address', 'phone'];
}
