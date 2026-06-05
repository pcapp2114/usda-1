<?php

/**
 * NASS Drupal 11 post-upgrade active configuration cleanup.
 *
 * Run with:
 *   ddev drush scr scripts/nass_d11_cleanup_active_config.php
 *
 * Purpose:
 * - Remove stale block placements for removed admin themes: adminimal_theme and seven.
 * - Remove stale theme settings config for removed themes.
 * - Remove adminimal_theme and seven from active core.extension theme list.
 * - Force Claro as the admin theme and NASS as the default theme.
 */

use Drupal\Core\Site\Settings;

$config_factory = \Drupal::configFactory();

$deleted = [];
$prefixes = [
  'block.block.adminimal_theme',
  'block.block.seven',
];

foreach ($prefixes as $prefix) {
  foreach ($config_factory->listAll($prefix) as $config_name) {
    $config_factory->getEditable($config_name)->delete();
    $deleted[] = $config_name;
  }
}

foreach (['adminimal_theme.settings', 'seven.settings'] as $config_name) {
  if ($config_factory->get($config_name)->getRawData()) {
    $config_factory->getEditable($config_name)->delete();
    $deleted[] = $config_name;
  }
}

$core_extension = $config_factory->getEditable('core.extension');
$themes = $core_extension->get('theme') ?: [];
unset($themes['adminimal_theme'], $themes['seven']);
$core_extension->set('theme', $themes)->save(TRUE);

$system_theme = $config_factory->getEditable('system.theme');
$system_theme->set('admin', 'claro');
$system_theme->set('default', 'nass');
$system_theme->save(TRUE);

\Drupal::service('kernel')->invalidateContainer();

echo "Deleted stale config objects:\n";
if ($deleted) {
  foreach ($deleted as $config_name) {
    echo " - {$config_name}\n";
  }
}
else {
  echo " - None found.\n";
}

echo "\nUpdated active config:";
echo "\n - core.extension: removed adminimal_theme and seven from theme list";
echo "\n - system.theme: admin=claro, default=nass\n";
