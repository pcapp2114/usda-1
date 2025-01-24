<?php

$databases['default']['default'] = [
  'database' => $_ENV['MYSQL_DATABASE'] ?: 'www_drupal_internal',
  'username' => $_ENV['MYSQL_USERNAME'] ?: 'www_drupal_user',
  'password' => $_ENV['MYSQL_PASSWORD'] ?: 'P@ss1234',
  'prefix' => '',
  'host' => $_ENV['MYSQL_HOST'] ?: 'va-dev-mysql-flex-01.mysql.database.usgovcloudapi.net',
  'port' => '3306',
  'namespace' => 'Drupal\\Core\\Database\\Driver\\mysql',
  'driver' => 'mysql',
  'init_commands' => [
	'isolation_level' => 'SET SESSION transaction_isolation=\'READ-COMMITTED\'',
  ],
  'pdo' => [
	PDO::MYSQL_ATTR_SSL_KEY => '/etc/ssl/certs/BaltimoreCyberTrustRoot.crt.pem',
  ],
];

$settings['hash_salt'] = '7d165ba7557fa0e02d19af79a15d7113f4b3ea14c79bd3ca98ae0cb431a3df6a';
$settings['skip_permissions_hardening'] = TRUE;
$settings['trusted_host_patterns'] = ['.*'];

$settings['config_sync_directory'] = '../config/sync';

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

$config['system.logging']['error_level'] = 'verbose';
$config['system.performance']['css']['preprocess'] = FALSE;
$config['system.performance']['js']['preprocess'] = FALSE;
