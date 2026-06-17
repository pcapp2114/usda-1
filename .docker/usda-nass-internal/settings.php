<?php

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

try {
  /* Database Information */
  if (!empty($_ENV['DRUPAL_MYSQL_DATABASE'])) {
    $databases['default']['default'] = [
      'database' => $_ENV['DRUPAL_MYSQL_DATABASE'],
      'username' => $_ENV['DRUPAL_MYSQL_USERNAME'],
      'password' => $_ENV['DRUPAL_MYSQL_PASSWORD'],
      'prefix' => '',
      'host' => $_ENV['DRUPAL_MYSQL_HOST'],
      'port' => '3306',
      'namespace' => 'Drupal\\Core\\Database\\Driver\\mysql',
      'driver' => 'mysql',
      'init_commands' => [
        'isolation_level' => 'SET SESSION transaction_isolation=\'READ-COMMITTED\'',
      ],
      'pdo' => [
        \PDO::MYSQL_ATTR_SSL_CA => $_ENV['DRUPAL_MYSQL_SSL'],
        //\PDO::MYSQL_ATTR_SSL_CA => '/etc/ssl/certs/ca-certificates.crt',
        //\PDO::MYSQL_ATTR_SSL_KEY => $_ENV['DRUPAL_MYSQL_SSL'],
        \PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
      ],
    ];
  }

  /* Trusted Hosts */
  $settings['trusted_host_patterns'] = [
    '^usda\.gov$',
    '^.+\.usda\.gov$',
    '^localhost$',
    '^127\.0\.0\.1$',
  ];

  /* Settings Variables */
  $settings['hash_salt'] = !empty($_ENV['DRUPAL_HASH_SALT']) ? $_ENV['DRUPAL_HASH_SALT'] : '';
  $settings['config_sync_directory'] = !empty($_ENV['DRUPAL_CONFIG_DIR']) ? $_ENV['DRUPAL_CONFIG_DIR'] : '';
  $settings['skip_permissions_hardening'] = TRUE;
  $settings['entity_update_batch_size'] = 50;
  $settings['entity_update_backup'] = TRUE;
  $settings['migrate_node_migrate_type_classic'] = FALSE;
  $settings['update_free_access'] = FALSE;
  $settings['file_scan_ignore_directories'] = [
    'vendor',
    'node_modules',
    'bower_components',
  ];
  $settings['state_cache'] = TRUE;

  $settings['http_client_config'] = [
    'timeout' => !empty($_ENV['DRUPAL_HTTP_CLIENT_CONF']) ? $_ENV['DRUPAL_HTTP_CLIENT_CONF'] : '',
  ];

  $settings['file_private_path'] = !empty($_ENV['DRUPAL_PRIVATE_FILES']) ? $_ENV['DRUPAL_PRIVATE_FILES'] : '../private';
  $settings['file_public_path'] = !empty($_ENV['DRUPAL_PUBLIC_FILES']) ? $_ENV['DRUPAL_PUBLIC_FILES'] : 'sites/default/files';

  /* Config Variables */
  $config['image.settings']['allow_insecure_derivatives'] = !empty($_ENV['DRUPAL_ALLOW_INSECURE_DERIVATIVES']) ? $_ENV['DRUPAL_ALLOW_INSECURE_DERIVATIVES'] : '';

  if (!empty($_ENV['DRUPAL_ENV_BG_COLOR'])) {
    $config['environment_indicator.indicator'] = [
      'bg_color' => $_ENV['DRUPAL_ENV_BG_COLOR'],
      'fg_color' => $_ENV['DRUPAL_ENV_FG_COLOR'],
      'name' => $_ENV['DRUPAL_ENV_NAME'],
    ];
  }

  $config['system.logging']['error_level'] = 'verbose';
  $config['core.extension']['module']['nass_quick_stats'] = 0;
  $config['dblog.settings']['row_limit'] = 10000;

  $config['system.performance']['cache']['page']['max_age'] = 0;
  $config['system.performance']['css']['preprocess'] = FALSE;
  $config['system.performance']['css']['gzip'] = FALSE;
  $config['system.performance']['js']['preprocess'] = FALSE;
  $config['system.performance']['js']['gzip'] = FALSE;

  $config['symfony_mailer.settings']['default_transport'] = 'sendmail';
  $config['symfony_mailer.mailer_transport.sendmail']['plugin'] = 'smtp';
  $config['symfony_mailer.mailer_transport.sendmail']['configuration']['user'] = '';
  $config['symfony_mailer.mailer_transport.sendmail']['configuration']['pass'] = '';
  $config['symfony_mailer.mailer_transport.sendmail']['configuration']['host'] = 'localhost';
  $config['symfony_mailer.mailer_transport.sendmail']['configuration']['port'] = '1025';

  $config['swiftmailer.transport']['transport'] = 'smtp';
  $config['swiftmailer.transport']['smtp_host'] = '127.0.0.1';
  $config['swiftmailer.transport']['smtp_port'] = '1025';
  $config['swiftmailer.transport']['smtp_encryption'] = '0';

  $settings['container_yamls'][] = DRUPAL_ROOT . '/sites/default/services.yml';

  if ($_ENV['DRUPAL_ENABLE_DEBUG'] == 'TRUE') {
    $settings['container_yamls'][] = DRUPAL_ROOT . '/sites/default/development-services.yml';

    $settings['cache']['bins']['render'] = 'cache.backend.null';
    $settings['cache']['bins']['page'] = 'cache.backend.null';
    $settings['cache']['bins']['dynamic_page_cache'] = 'cache.backend.null';

    $settings['extension_discovery_scan_tests'] = FALSE;
    $settings['twig_debug'] = TRUE;

    $config['system.performance']['css']['preprocess'] = FALSE;
    $config['system.performance']['js']['preprocess'] = FALSE;
  }
}
catch (\Exception $e) {
  print "An error has occurred trying to load the configuration. Please check if your environment configuration is properly set up. Error: " . $e;
}
