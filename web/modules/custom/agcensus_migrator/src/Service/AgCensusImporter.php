<?php

declare(strict_types=1);

namespace Drupal\agcensus_migrator\Service;

use Drupal\Component\Datetime\TimeInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\File\FileSystemInterface;
use Drupal\file\Entity\File;
use Drupal\paragraphs\Entity\Paragraph;
use GuzzleHttp\ClientInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerChannelFactoryInterface;

/**
 * Imports normalized Ag Census JSON into nodes and paragraphs.
 */
final class AgCensusImporter {
  use LoggerAwareTrait;

  public function __construct(
    private readonly EntityTypeManagerInterface $entityTypeManager,
    private readonly FileSystemInterface $fileSystem,
    private readonly ClientInterface $httpClient,
    LoggerChannelFactoryInterface $loggerFactory,
    private readonly TimeInterface $time,
  ) {
    $this->logger = $loggerFactory->get('agcensus_migrator');
  }

  /**
   * Import a normalized JSON file.
   *
   * @return array<string, mixed>
   *   Summary data.
   */
  public function importFromJsonFile(string $jsonPath, bool $downloadFiles = TRUE): array {
    if (!is_file($jsonPath)) {
      throw new \InvalidArgumentException(sprintf('JSON file not found: %s', $jsonPath));
    }

    $raw = file_get_contents($jsonPath);
    if ($raw === FALSE) {
      throw new \RuntimeException(sprintf('Unable to read %s', $jsonPath));
    }

    $data = json_decode($raw, TRUE, 512, JSON_THROW_ON_ERROR);
    if (!is_array($data)) {
      throw new \RuntimeException('Decoded JSON was not an array.');
    }

    return $this->importRecord($data, $downloadFiles);
  }

  /**
   * Import one normalized record.
   */
  public function importRecord(array $data, bool $downloadFiles = TRUE): array {
    $node_storage = $this->entityTypeManager->getStorage('node');
    $paragraphs = [];
    $sectionCount = 0;
    $itemCount = 0;
    $fileCount = 0;

    foreach (($data['sections'] ?? []) as $section) {
      $items = [];
      foreach (($section['items'] ?? []) as $item) {
        $links = [];
        foreach (($item['links'] ?? []) as $link) {
          $linkParagraph = Paragraph::create([
            'type' => 'agc_action_link',
            'field_heading' => $link['label'] ?? '',
            'field_link_url' => !empty($link['url']) ? [
              'uri' => $link['url'],
              'title' => $link['label'] ?? '',
            ] : NULL,
            'field_file_format' => $link['format'] ?? '',
          ]);

          if ($downloadFiles && !empty($link['url']) && $this->looksLikeDownloadLink($link['url'], $link['format'] ?? NULL)) {
            $file = $this->downloadRemoteFile($link['url']);
            if ($file) {
              $linkParagraph->set('field_file_asset', ['target_id' => $file->id()]);
              $fileCount++;
            }
          }

          $linkParagraph->save();
          $links[] = [
            'target_id' => $linkParagraph->id(),
            'target_revision_id' => $linkParagraph->getRevisionId(),
          ];
        }

        $itemParagraph = Paragraph::create([
          'type' => 'agc_resource_item',
          'field_heading' => $item['title'] ?? '',
          'field_intro' => [
            'value' => $item['description'] ?? '',
            'format' => 'basic_html',
          ],
          'field_group_label' => $item['group_label'] ?? '',
          'field_item_release_date' => !empty($item['release_date']) ? $item['release_date'] : NULL,
          'field_links' => $links,
        ]);
        $itemParagraph->save();
        $items[] = [
          'target_id' => $itemParagraph->id(),
          'target_revision_id' => $itemParagraph->getRevisionId(),
        ];
        $itemCount++;
      }

      $sectionParagraph = Paragraph::create([
        'type' => 'agc_section',
        'field_heading' => $section['heading'] ?? '',
        'field_intro' => [
          'value' => $section['intro'] ?? '',
          'format' => 'basic_html',
        ],
        'field_items' => $items,
      ]);
      $sectionParagraph->save();
      $paragraphs[] = [
        'target_id' => $sectionParagraph->id(),
        'target_revision_id' => $sectionParagraph->getRevisionId(),
      ];
      $sectionCount++;
    }

    $title = $data['page']['title'] ?? ('Ag Census ' . ($data['page']['year'] ?? 'Import'));
    $sourceUrl = $data['page']['source_url'] ?? '';

    $existing = NULL;
    if ($sourceUrl !== '') {
      $results = $node_storage->loadByProperties([
        'type' => 'ag_census_page',
        'field_source_url.uri' => $sourceUrl,
      ]);
      $existing = $results ? reset($results) : NULL;
    }

    $nodeValues = [
      'type' => 'ag_census_page',
      'title' => $title,
      'field_census_year' => $data['page']['year'] ?? NULL,
      'field_source_variant' => $data['page']['variant'] ?? NULL,
      'field_source_url' => $sourceUrl !== '' ? ['uri' => $sourceUrl] : NULL,
      'field_release_date' => !empty($data['page']['release_date']) ? $data['page']['release_date'] : NULL,
      'field_summary' => [
        'value' => $data['page']['summary'] ?? '',
        'format' => 'basic_html',
      ],
      'field_legacy_html' => [
        'value' => $data['page']['legacy_html'] ?? '',
        'format' => 'plain_text',
      ],
      'field_sections' => $paragraphs,
      'status' => 1,
    ];

    if ($existing) {
      $existing->setNewRevision(TRUE);
      foreach ($nodeValues as $key => $value) {
        $existing->set($key, $value);
      }
      $existing->save();
      $node = $existing;
    }
    else {
      $node = $node_storage->create($nodeValues);
      $node->save();
    }

    return [
      'nid' => $node->id(),
      'title' => $node->label(),
      'sections' => $sectionCount,
      'items' => $itemCount,
      'downloaded_files' => $fileCount,
    ];
  }

  /**
   * Download a remote file and create a managed file entity.
   */
  private function downloadRemoteFile(string $url): ?File {
    try {
      $response = $this->httpClient->request('GET', $url, [
        'timeout' => 30,
        'http_errors' => FALSE,
      ]);
      if ($response->getStatusCode() >= 400) {
        $this->logger->warning('Skipping file download {url}. HTTP {code}', [
          'url' => $url,
          'code' => $response->getStatusCode(),
        ]);
        return NULL;
      }

      $pathInfo = pathinfo(parse_url($url, PHP_URL_PATH) ?: 'download');
      $filename = preg_replace('/[^A-Za-z0-9._-]+/', '_', $pathInfo['basename'] ?? ('download_' . $this->time->getCurrentTime()));
      $directory = 'public://agcensus';
      $this->fileSystem->prepareDirectory($directory, FileSystemInterface::CREATE_DIRECTORY | FileSystemInterface::MODIFY_PERMISSIONS);
      $destination = $directory . '/' . $filename;
      $written = file_save_data((string) $response->getBody(), $destination, FileSystemInterface::EXISTS_RENAME);
      if ($written instanceof File) {
        $written->setPermanent();
        $written->save();
        return $written;
      }
    }
    catch (\Throwable $e) {
      $this->logger->warning('File download failed for {url}: {message}', [
        'url' => $url,
        'message' => $e->getMessage(),
      ]);
    }

    return NULL;
  }

  /**
   * Check whether a URL appears to be a downloadable asset.
   */
  private function looksLikeDownloadLink(string $url, ?string $format = NULL): bool {
    $needle = strtolower($format ?? $url);
    foreach (['.pdf', '.txt', '.csv', '.xls', '.xlsx', '.zip', 'pdf', 'txt', 'csv', 'xls', 'xlsx'] as $suffix) {
      if (str_contains($needle, $suffix)) {
        return TRUE;
      }
    }
    return FALSE;
  }
}
