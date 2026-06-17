<?php

namespace Drupal\nass_tools\Service;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Extension\ModuleExtensionList;
use Psr\Log\LoggerInterface;

final class TxFileLocator {

  public function __construct(
    private readonly ConfigFactoryInterface $configFactory,
    private readonly ModuleExtensionList $moduleExtensionList,
    private readonly LoggerInterface $logger,
  ) {}

  public function getActiveDirectory(): ?string {
    $config = $this->configFactory->get('nass_release.adminsettings');
    $configured = trim((string) $config->get('tx_input_dir'));
    if ($configured !== '' && is_dir($configured) && is_readable($configured)) {
      return $configured;
    }

    if ($config->get('tx_use_module_data_fallback') !== FALSE) {
      $fallback = DRUPAL_ROOT . '/' . $this->moduleExtensionList->getPath('nass_tools') . '/data';
      if (is_dir($fallback) && is_readable($fallback)) {
        return $fallback;
      }
    }

    if ($configured !== '') {
      $this->logger->warning('Configured TX input directory is not readable: @dir', ['@dir' => $configured]);
    }
    return NULL;
  }

  public function getFiles(): array {
    $dir = $this->getActiveDirectory();
    if (!$dir) {
      return [];
    }
    $files = glob(rtrim($dir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . '*.tx') ?: [];
    sort($files);
    return $files;
  }

  public function getManifest(): array {
    $manifest = [];
    foreach ($this->getFiles() as $file) {
      $manifest[$file] = [
        'mtime' => filemtime($file) ?: 0,
        'size' => filesize($file) ?: 0,
        'hash' => hash_file('sha256', $file),
      ];
    }
    return $manifest;
  }

}
