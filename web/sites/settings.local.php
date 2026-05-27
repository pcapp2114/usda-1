<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
  /**
   * Local settings file.
   *
   * This file is intended for DDEV/local usage. Values should come from .env
   * using the same DRUPAL_* variable names used by AWS Secrets Manager where
   * possible, so local/dev/stage/prod stay aligned.
   */
  $get_var = static function (string $name, $default = NULL) {
    $value = getenv($name);
    return ($value === FALSE || $value === '') ? $default : $value;
  };

  $to_bool = static function ($value): bool {
    if (is_bool($value)) {
      return $value;
    }
    return in_array(strtolower((string) $value), ['1', 'true', 'yes', 'on'], TRUE);
  };

  /* Database Information */
  $databases['default']['default'] = [
    'database' => $get_var('DRUPAL_MYSQL_DATABASE'),
    'username' => $get_var('DRUPAL_MYSQL_USERNAME'),
    'password' => $get_var('DRUPAL_MYSQL_PASSWORD'),
    'prefix' => $get_var('DRUPAL_MYSQL_PREFIX', ''),
    'host' => $get_var('DRUPAL_MYSQL_HOST'),
    'port' => $get_var('DRUPAL_MYSQL_PORT', '3306'),
    'namespace' => 'Drupal\\Core\\Database\\Driver\\mysql',
    'driver' => $get_var('DRUPAL_MYSQL_DRIVER', 'mysql'),
  ];

  /* Trusted Hosts */
  $settings['trusted_host_patterns'] = ['.*'];

  /* Environment-controlled settings */
  $enable_debug = $to_bool($get_var('DRUPAL_ENABLE_DEBUG', '1'));
  $active_config_split = strtolower((string) $get_var('DRUPAL_CONFIG_SPLIT_ACTIVE', $get_var('DRUPAL_ENV', 'local')));
  $rebuild_access = $to_bool($get_var('DRUPAL_REBUILD_ACCESS', '1'));
  $logging_error_level = (string) $get_var('DRUPAL_SYSTEM_LOGGING_ERROR_LEVEL', $enable_debug ? 'verbose' : 'hide');

  /* Settings Variables */
  $settings = array_merge($settings, [
    'hash_salt' => $get_var('DRUPAL_HASH_SALT'),
    'config_sync_directory' => $get_var('DRUPAL_CONFIG_DIR', '../config/sync'),
    'skip_permissions_hardening' => $to_bool($get_var('DRUPAL_SKIP_PERMISSION_HARDENING', '1')),
    'entity_update_batch_size' => 50,
    'entity_update_backup' => TRUE,
    'migrate_node_migrate_type_classic' => FALSE,
    'update_free_access' => FALSE,
    'rebuild_access' => $rebuild_access,
    'file_scan_ignore_directories' => [
      'vendor',
      'node_modules',
      'bower_components',
    ],
    'state_cache' => $to_bool($get_var('DRUPAL_STATE_CACHE', '1')),
    'http_client_config' => [
      'timeout' => (int) $get_var('DRUPAL_HTTP_CLIENT_CONF', '60'),
    ],
    'file_private_path' => $get_var('DRUPAL_PRIVATE_FILES', '../private/files'),
    'file_public_path' => $get_var('DRUPAL_PUBLIC_FILES', 'sites/default/files'),
    'file_temp_path' => $get_var('DRUPAL_TEMP_DIR', '/tmp'),
  ]);

  /* Config Split */
  $config['config_split.config_split.local']['status'] = ($active_config_split === 'local');
  $config['config_split.config_split.dev']['status'] = ($active_config_split === 'dev');
  $config['config_split.config_split.stage']['status'] = ($active_config_split === 'stage');
  $config['config_split.config_split.prod']['status'] = ($active_config_split === 'prod');

  /* Config Variables */
  $config['image.settings']['allow_insecure_derivatives'] = $to_bool($get_var('DRUPAL_ALLOW_INSECURE_DERIVATIVES', '1'));
  $config['environment_indicator.indicator'] = [
    'bg_color' => $get_var('DRUPAL_ENV_BG_COLOR', '#006400'),
    'fg_color' => $get_var('DRUPAL_ENV_FG_COLOR', '#ffffff'),
    'name' => $get_var('DRUPAL_ENV_NAME', 'Local'),
  ];

  /* Salesforce Sandbox Configuration */
  $config['salesforce_auth.salesforce_auth_config.cba_sf_auth']['login_url'] = $get_var('DRUPAL_SALESFORCE_LOGIN_URL', 'https://test.salesforce.com');
  $config['salesforce.settings']['salesforce_url'] = $get_var('DRUPAL_SALESFORCE_URL', 'https://creditbuildersalliance--test.lightning.force.com');
  $config['salesforce.settings']['salesforce_api_url'] = $get_var('DRUPAL_SALESFORCE_API_URL', 'https://creditbuildersalliance--test.my.salesforce.com');

  $config = array_merge($config, [
    'system.logging' => ['error_level' => $logging_error_level],
    'dblog.settings.row_limit' => 10000,
    'system.performance' => [
      'cache.page.max_age' => 0,
      'css.preprocess' => FALSE,
      'css.gzip' => FALSE,
      'js.preprocess' => FALSE,
      'js.gzip' => FALSE,
    ],
    'symfony_mailer.settings' => [
      'default_transport' => 'sendmail',
      'mailer_transport.sendmail' => [
        'plugin' => 'smtp',
        'configuration' => [
          'user' => '',
          'pass' => '',
          'host' => 'localhost',
          'port' => '1025',
        ],
      ],
    ],
    'swiftmailer.transport' => [
      'transport' => 'smtp',
      'smtp_host' => '127.0.0.1',
      'smtp_port' => '1025',
      'smtp_encryption' => '0',
    ],
  ]);

  if (is_readable(__DIR__ . '/services.yml')) {
    $settings['container_yamls'][] = __DIR__ . '/services.yml';
  }

  if ($enable_debug && is_readable(__DIR__ . '/development-services.yml')) {
    $settings['container_yamls'][] = __DIR__ . '/development-services.yml';

    $settings['cache']['bins']['render'] = 'cache.backend.null';
    $settings['cache']['bins']['page'] = 'cache.backend.null';
    $settings['cache']['bins']['dynamic_page_cache'] = 'cache.backend.null';

    $settings['extension_discovery_scan_tests'] = FALSE;
    $settings['twig_debug'] = TRUE;
    $settings['twig_auto_reload'] = TRUE;
    $settings['twig_cache'] = FALSE;
  }
}
catch (\Exception $e) {
  print "An error occurred while loading the configuration. Please ensure your environment settings are correct. Error: " . $e->getMessage();
}
