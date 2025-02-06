<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {

  /* Database Information */
  if(isset($_ENV['MYSQL_DATABASE']) && !empty($_ENV['MYSQL_DATABASE'])) {
    $databases['default']['default'] = [
      'database' => $_ENV['MYSQL_DATABASE'],
      'username' => $_ENV['MYSQL_USERNAME'],
      'password' => $_ENV['MYSQL_PASSWORD'],
      'prefix' => '',
      'host' => $_ENV['MYSQL_HOST'],
      'port' => '3306',
      'namespace' => 'Drupal\\Core\\Database\\Driver\\mysql',
      'driver' => 'mysql',
      'init_commands' => [
        'isolation_level' => 'SET SESSION transaction_isolation=\'READ-COMMITTED\'',
      ],
      'pdo' => [
        PDO::MYSQL_ATTR_SSL_KEY => $_ENV['MYSQL_SSL'],
      ],
    ];
  }

  /* Trusted Hosts */
  $settings['trusted_host_patterns'] = [];
  if(isset($_ENV['trusted_env_1']) && !empty($_ENV['trusted_env_1'])) {
    for($i=1; $i<=10; $i++) {
      if(isset($_ENV['trusted_env_' . $i]) && !empty($_ENV['trusted_env_' . $i])) {
        array_push($settings['trusted_host_patterns'], $_ENV['trusted_env_' . $i]);
      }
    }
  }

  /* Settings Variables */
  $settings['hash_salt'] = (isset($_ENV['HASH_SALT']) && !empty($_ENV['HASH_SALT'])) ? $_ENV['HASH_SALT'] : '';
  $settings['config_sync_directory'] = (isset($_ENV['CONFIG_DIR']) && !empty($_ENV['CONFIG_DIR'])) ? $_ENV['CONFIG_DIR'] : '';
  $settings['skip_permissions_hardening'] = TRUE;
  $settings['entity_update_batch_size'] = 50;
  $settings['entity_update_backup'] = TRUE;
  $settings['migrate_node_migrate_type_classic'] = FALSE;
  $settings['update_free_access'] = FALSE;
  $settings['file_scan_ignore_directories'] = ['vendor', 'node_modules', 'bower_components'];

  $settings['http_client_config'] = [
    'timeout' => (isset($_ENV['HTTP_CLIENT_CONF']) && !empty($_ENV['HTTP_CLIENT_CONF'])) ? $_ENV['HTTP_CLIENT_CONF'] : ''
  ];

  $settings['file_private_path'] = (isset($_ENV['PRIVATE_FILES']) && !empty($_ENV['PRIVATE_FILES'])) ? $_ENV['PRIVATE_FILES'] : '../private/files';
  $settings['file_public_path'] = (isset($_ENV['PUBLIC_FILES']) && !empty($_ENV['PUBLIC_FILES'])) ? $_ENV['PUBLIC_FILES'] : 'sites/default/files';

  /* Config Variables */
  $config['image.settings']['allow_insecure_derivatives'] = (isset($_ENV['ALLOW_INSECURE_DERIVATIVES']) && !empty($_ENV['ALLOW_INSECURE_DERIVATIVES'])) ? $_ENV['ALLOW_INSECURE_DERIVATIVES'] : '';

  if(isset($_ENV['ENV_BG_COLOR']) && !empty($_ENV['ENV_BG_COLOR'])) {
    $config['environment_indicator.indicator'] = [
      'bg_color' => $_ENV['ENV_BG_COLOR'],
      'fg_color' => $_ENV['ENV_FG_COLOR'],
      'name' => $_ENV['ENV_NAME'],
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

  if(isset($_ENV['ENABLE_DEBUG']) && $_ENV['ENABLE_DEBUG'] == 'TRUE') {
    $settings['container_yamls'][] = DRUPAL_ROOT . '/sites/default/development-services.yml';

    $settings['cache']['bins']['render'] = 'cache.backend.null';
    $settings['cache']['bins']['page'] = 'cache.backend.null';
    $settings['cache']['bins']['dynamic_page_cache'] = 'cache.backend.null';

    $settings['extension_discovery_scan_tests'] = FALSE;
    $settings['twig_debug'] = TRUE;

    $config['system.performance']['css']['preprocess'] = FALSE;
    $config['system.performance']['js']['preprocess'] = FALSE;
  }

} catch(\Exception $e) {
  print "An error has occurred trying to load the configuration. Please check if your enviroment configuration is properly set up. Error: " . $e;
}
