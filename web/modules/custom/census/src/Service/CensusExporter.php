<?php

namespace Drupal\census\Service;

use Drupal\Component\Datetime\TimeInterface;
use Drupal\Core\File\FileSystemInterface;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Scrapes NASS Census pages, downloads local files, and writes manifests.
 */
final class CensusExporter {

  private const DOWNLOAD_EXTENSIONS = [
    'pdf', 'txt', 'csv', 'zip', 'xls', 'xlsx', 'doc', 'docx', 'rtf', 'xml', 'json',
  ];

  /**
   * State FIPS prefixes used by NASS county/profile filenames.
   *
   * Examples: cp01001.pdf => Alabama / Autauga from label;
   * cd0101.pdf => Alabama / district_01.
   */
  private const STATE_FIPS = [
    '01' => 'Alabama', '02' => 'Alaska', '04' => 'Arizona', '05' => 'Arkansas',
    '06' => 'California', '08' => 'Colorado', '09' => 'Connecticut', '10' => 'Delaware',
    '11' => 'District of Columbia', '12' => 'Florida', '13' => 'Georgia', '15' => 'Hawaii',
    '16' => 'Idaho', '17' => 'Illinois', '18' => 'Indiana', '19' => 'Iowa',
    '20' => 'Kansas', '21' => 'Kentucky', '22' => 'Louisiana', '23' => 'Maine',
    '24' => 'Maryland', '25' => 'Massachusetts', '26' => 'Michigan', '27' => 'Minnesota',
    '28' => 'Mississippi', '29' => 'Missouri', '30' => 'Montana', '31' => 'Nebraska',
    '32' => 'Nevada', '33' => 'New Hampshire', '34' => 'New Jersey', '35' => 'New Mexico',
    '36' => 'New York', '37' => 'North Carolina', '38' => 'North Dakota', '39' => 'Ohio',
    '40' => 'Oklahoma', '41' => 'Oregon', '42' => 'Pennsylvania', '44' => 'Rhode Island',
    '45' => 'South Carolina', '46' => 'South Dakota', '47' => 'Tennessee', '48' => 'Texas',
    '49' => 'Utah', '50' => 'Vermont', '51' => 'Virginia', '53' => 'Washington',
    '54' => 'West Virginia', '55' => 'Wisconsin', '56' => 'Wyoming',
  ];

  /**
   * HTML child pages we are willing to crawl one level deep.
   */
  private const CRAWLABLE_ROUTE_MARKERS = [
    '/Online_Resources/Watersheds/',
    '/Online_Resources/County_Profiles/',
    '/Online_Resources/Congressional_District_Profiles/',
    '/Online_Resources/Typology/',
    '/Online_Resources/Rankings_of_Market_Value/',
    '/Online_Resources/Congressional_District_Rankings/',
    '/Online_Resources/Hemp/',
  ];

  public function __construct(
    private readonly ClientInterface $httpClient,
    private readonly FileSystemInterface $fileSystem,
    private readonly CensusPathNormalizer $normalizer,
    private readonly LoggerChannelFactoryInterface $loggerFactory,
    private readonly TimeInterface $time,
  ) {}

  /**
   * Exports one source page and, for known Online Resource pages, direct children.
   *
   * @return array<string, mixed>
   *   Export summary and manifest locations.
   */
  public function export(string $url, string $year, bool $dry_run = FALSE, bool $overwrite = TRUE): array {
    $url = trim($url);
    $year = trim($year);

    if (!$this->isValidUrl($url)) {
      throw new \InvalidArgumentException('The --url option must be a valid absolute URL.');
    }
    if (!preg_match('/^\d{4}$/', $year)) {
      throw new \InvalidArgumentException('The --year option must be a four-digit year, for example 2022.');
    }

    $base_public_uri = 'public://agcensus/' . $year;
    if (!$dry_run) {
      $this->fileSystem->prepareDirectory($base_public_uri, FileSystemInterface::CREATE_DIRECTORY | FileSystemInterface::MODIFY_PERMISSIONS);
    }

    $state = [
      'groups' => [],
      'items' => [],
      'warnings' => [],
      'usedFilenamesByDirectory' => [],
      'visitedPages' => [],
      'downloaded' => 0,
      'skipped' => 0,
      'failed' => 0,
      'pagesProcessed' => 0,
      'linksFound' => 0,
      'childPagesQueued' => 0,
    ];

    $page_result = $this->exportPage($url, $year, $base_public_uri, $dry_run, $overwrite, $state, 0);

    $manifest = [
      'schema' => 'https://nass.local/schema/census-export-manifest-v1.json',
      'version' => '1.4',
      'year' => $year,
      'title' => $page_result['pageTitle'],
      'sourceUrl' => $url,
      'sourcePathAfterYear' => $this->relativePathAfterYear($url, $year),
      'localBaseUri' => $page_result['localBaseUri'],
      'localBasePath' => $this->publicRelativePath($page_result['localBaseUri']),
      'generatedAt' => gmdate('c', $this->time->getRequestTime()),
      'dryRun' => $dry_run,
      'scope' => [
        'selector' => 'div.contentRight',
        'behavior' => 'Only links inside div.contentRight are considered. Header, menu, sidebar, and footer links are ignored.',
        'routeAwarePlacement' => TRUE,
        'crawlBehavior' => 'Known Online Resources index pages may crawl one level of child HTML pages when links are inside div.contentRight and stay under the same AgCensus year.',
      ],
      'normalization' => [
        'folders' => 'lowercase, underscores, no spaces, no special characters',
        'files' => 'derived from contentRight link context; original filename retained in manifest',
        'sectionPriority' => ['route-specific Census rule', 'table.reports caption', 'nearest table caption', 'nearest heading', 'Files'],
        'titlePriority' => ['table row context plus link type', 'html_link_text', 'pdf_detected_title', 'original_filename'],
      ],
      'routeRules' => $this->routeRulesSummary(),
      'groups' => array_values($state['groups']),
      'items' => $state['items'],
      'warnings' => $state['warnings'],
      'pagesProcessed' => $state['pagesProcessed'],
      'visitedPages' => array_values($state['visitedPages']),
    ];

    $page_manifest_uri = $page_result['localBaseUri'] . '/manifest.json';
    $report_uri = $page_result['localBaseUri'] . '/migration-report.json';

    $report = [
      'sourcePage' => $url,
      'year' => $year,
      'pageTitle' => $page_result['pageTitle'],
      'scopeSelector' => 'div.contentRight',
      'linksFound' => $state['linksFound'],
      'childPagesQueued' => $state['childPagesQueued'],
      'pagesProcessed' => $state['pagesProcessed'],
      'filesDownloaded' => $state['downloaded'],
      'filesSkipped' => $state['skipped'],
      'filesFailed' => $state['failed'],
      'dryRun' => $dry_run,
      'pageManifestUri' => $page_manifest_uri,
      'warnings' => $state['warnings'],
    ];

    if (!$dry_run) {
      $this->writeJson($page_manifest_uri, $manifest);
      $this->writeJson($report_uri, $report);
      $this->updateYearManifest($base_public_uri, $year, $url, $page_manifest_uri, $page_result['pageTitle']);
    }

    return [
      'year' => $year,
      'sourceUrl' => $url,
      'pageTitle' => $page_result['pageTitle'],
      'localBaseUri' => $page_result['localBaseUri'],
      'pageManifestUri' => $page_manifest_uri,
      'reportUri' => $report_uri,
      'linksFound' => $state['linksFound'],
      'childPagesQueued' => $state['childPagesQueued'],
      'pagesProcessed' => $state['pagesProcessed'],
      'filesDownloaded' => $state['downloaded'],
      'filesSkipped' => $state['skipped'],
      'filesFailed' => $state['failed'],
      'dryRun' => $dry_run,
      'warnings' => $state['warnings'],
    ];
  }

  /**
   * Exports one page into a shared manifest state.
   *
   * @param array<string, mixed> $state
   *   Shared export state.
   *
   * @return array<string, string>
   *   Page title and local base URI.
   */
  private function exportPage(string $url, string $year, string $base_public_uri, bool $dry_run, bool $overwrite, array &$state, int $depth): array {
    $canonical_url = $this->canonicalizePageUrl($url);
    if (isset($state['visitedPages'][$canonical_url])) {
      return [
        'pageTitle' => $state['visitedPages'][$canonical_url]['pageTitle'] ?? 'Census Export',
        'localBaseUri' => $state['visitedPages'][$canonical_url]['localBaseUri'] ?? $base_public_uri,
      ];
    }

    $page_html = $this->fetchText($url);
    $content_html = $this->extractContentRightHtml($page_html);
    $page_title = $this->extractPageTitle($content_html ?: $page_html) ?: 'Census Export';
    $page_public_uri = $this->pageBaseUri($url, $year, $base_public_uri);

    $state['visitedPages'][$canonical_url] = [
      'url' => $url,
      'pageTitle' => $page_title,
      'localBaseUri' => $page_public_uri,
      'depth' => $depth,
    ];
    $state['pagesProcessed']++;

    if (!$dry_run) {
      $this->fileSystem->prepareDirectory($page_public_uri, FileSystemInterface::CREATE_DIRECTORY | FileSystemInterface::MODIFY_PERMISSIONS);
    }

    $links = $this->extractDownloadLinks($page_html, $url, $year);
    $state['linksFound'] += count($links);

    foreach ($links as $link) {
      $this->exportLink($link, $url, $year, $page_public_uri, $dry_run, $overwrite, $state);
    }

    if ($depth < 1 && $this->shouldCrawlChildLinks($url)) {
      $child_pages = $this->extractChildPageLinks($page_html, $url, $year);
      $state['childPagesQueued'] += count($child_pages);
      foreach ($child_pages as $child_url) {
        $this->exportPage($child_url, $year, $base_public_uri, $dry_run, $overwrite, $state, $depth + 1);
      }
    }

    return [
      'pageTitle' => $page_title,
      'localBaseUri' => $page_public_uri,
    ];
  }

  /**
   * @param array<string, mixed> $link
   * @param array<string, mixed> $state
   */
  private function exportLink(array $link, string $page_url, string $year, string $page_public_uri, bool $dry_run, bool $overwrite, array &$state): void {
    $source_url = $link['url'];
    $html_label = $this->normalizer->cleanLabel($link['label']) ?: $this->normalizer->readableLabel($source_url);
    $placement = $this->routeAwarePlacement($page_url, $source_url, $html_label, $link, $year, $page_public_uri);

    $group_label = $placement['groupLabel'];
    $group_id = $this->normalizer->slug($group_label . ' ' . $placement['targetDir']);
    $normalized_section_path = $placement['sectionPath'];
    $target_dir = $placement['targetDir'];

    if (!isset($state['groups'][$group_id])) {
      $state['groups'][$group_id] = [
        'id' => $group_id,
        'label' => $group_label,
        'path' => $normalized_section_path,
        'localUri' => $target_dir,
        'display' => $this->guessDisplay($group_label),
        'items' => [],
      ];
    }

    $directory_key = $target_dir;
    $state['usedFilenamesByDirectory'][$directory_key] ??= [];
    $filename_label = $placement['filenameLabel'] ?? $html_label;
    $normalized_filename = $this->normalizer->normalizeFilename($filename_label, $source_url, $state['usedFilenamesByDirectory'][$directory_key]);
    $target_uri = $target_dir . '/' . $normalized_filename;

    $pdf_detected_title = NULL;
    $file_size = NULL;
    $sha256 = NULL;
    $mime = $this->guessMimeType($source_url);
    $status = 'planned';
    $error = NULL;

    if (!$dry_run) {
      try {
        $this->fileSystem->prepareDirectory($target_dir, FileSystemInterface::CREATE_DIRECTORY | FileSystemInterface::MODIFY_PERMISSIONS);
        if (!$overwrite && file_exists($this->fileSystem->realpath($target_uri))) {
          $status = 'skipped_exists';
          $state['skipped']++;
        }
        else {
          $body = $this->downloadBinary($source_url);
          $this->fileSystem->saveData($body, $target_uri, FileSystemInterface::EXISTS_REPLACE);
          $state['downloaded']++;
          $status = 'downloaded';
          $file_size = strlen($body);
          $sha256 = hash('sha256', $body);
          if (strtolower(pathinfo(parse_url($source_url, PHP_URL_PATH) ?: '', PATHINFO_EXTENSION)) === 'pdf') {
            $pdf_detected_title = $this->detectPdfTitle($body);
          }
        }
      }
      catch (\Throwable $e) {
        $state['failed']++;
        $status = 'failed';
        $error = $e->getMessage();
        $state['warnings'][] = [
          'sourceUrl' => $source_url,
          'issue' => 'Download failed: ' . $e->getMessage(),
        ];
        $this->loggerFactory->get('census')->error('Failed exporting @url: @message', [
          '@url' => $source_url,
          '@message' => $e->getMessage(),
        ]);
      }
    }

    $item = [
      'id' => $this->normalizer->slug(pathinfo($normalized_filename, PATHINFO_FILENAME)),
      'label' => $html_label,
      'type' => strtolower(pathinfo($normalized_filename, PATHINFO_EXTENSION)),
      'mime' => $mime,
      'localPath' => $this->publicRelativePath($target_uri),
      'localUri' => $target_uri,
      'sectionLabel' => $group_label,
      'sectionPath' => $placement['rawSectionPath'],
      'normalizedSectionPath' => $normalized_section_path,
      'routeRule' => $placement['routeRule'],
      'normalizedFilename' => $normalized_filename,
      'originalFilename' => basename(rawurldecode(parse_url($source_url, PHP_URL_PATH) ?: '')),
      'sourceUrl' => $source_url,
      'sourcePageUrl' => $page_url,
      'titleSource' => $link['titleSource'] ?? 'html_context',
      'pdfDetectedTitle' => $pdf_detected_title,
      'sizeBytes' => $file_size,
      'sha256' => $sha256,
      'status' => $status,
    ];
    if ($error !== NULL) {
      $item['error'] = $error;
    }

    $state['items'][] = $item;
    $state['groups'][$group_id]['items'][] = $item;
  }

  private function fetchText(string $url): string {
    try {
      $response = $this->httpClient->request('GET', $url, [
        'headers' => [
          'User-Agent' => 'Drupal census export test/1.4',
        ],
        'timeout' => 60,
        'connect_timeout' => 20,
      ]);
    }
    catch (GuzzleException $e) {
      throw new \RuntimeException('Unable to fetch source page: ' . $e->getMessage(), 0, $e);
    }

    if ($response->getStatusCode() >= 400) {
      throw new \RuntimeException('Source page returned HTTP ' . $response->getStatusCode());
    }
    return (string) $response->getBody();
  }

  private function downloadBinary(string $url): string {
    try {
      $response = $this->httpClient->request('GET', $url, [
        'headers' => [
          'User-Agent' => 'Drupal census export test/1.4',
        ],
        'timeout' => 180,
        'connect_timeout' => 20,
      ]);
    }
    catch (GuzzleException $e) {
      throw new \RuntimeException($e->getMessage(), 0, $e);
    }

    if ($response->getStatusCode() >= 400) {
      throw new \RuntimeException('HTTP ' . $response->getStatusCode());
    }
    return (string) $response->getBody();
  }

  /**
   * Extracts downloadable links only from the main NASS content area.
   *
   * @return array<int, array<string, mixed>>
   */
  private function extractDownloadLinks(string $html, string $base_url, string $year): array {
    $dom = new \DOMDocument();
    libxml_use_internal_errors(TRUE);
    $dom->loadHTML($html, LIBXML_NOERROR | LIBXML_NOWARNING | LIBXML_NONET);
    libxml_clear_errors();

    $xpath = new \DOMXPath($dom);
    $content = $this->getContentRightNode($xpath);
    if (!$content instanceof \DOMElement) {
      throw new \RuntimeException('Could not find div.contentRight in the source page. The export was stopped to avoid scraping menu/footer links.');
    }

    $anchors = $xpath->query('.//a[@href]', $content);
    $links = [];
    $seen = [];

    foreach ($anchors as $anchor) {
      if (!$anchor instanceof \DOMElement) {
        continue;
      }
      $href = trim($anchor->getAttribute('href'));
      if ($href === '' || str_starts_with($href, '#') || str_starts_with(strtolower($href), 'mailto:')) {
        continue;
      }
      $absolute = $this->absolutizeUrl($href, $base_url);
      if (!$this->isSameCensusYearUrl($absolute, $year)) {
        continue;
      }
      $extension = strtolower(pathinfo(parse_url($absolute, PHP_URL_PATH) ?: '', PATHINFO_EXTENSION));
      if (!in_array($extension, self::DOWNLOAD_EXTENSIONS, TRUE)) {
        continue;
      }
      if (isset($seen[$absolute])) {
        continue;
      }
      $seen[$absolute] = TRUE;

      $context = $this->detectLinkContext($anchor);
      $label = $this->deriveFileLabel($anchor, $absolute, $context);

      $links[] = [
        'url' => $absolute,
        'label' => $label,
        'group' => $context['group'],
        'sectionPath' => $context['sectionPath'],
        'rowLabel' => $context['rowLabel'],
        'titleSource' => $context['titleSource'],
      ];
    }

    return $links;
  }

  /**
   * Extracts one-level child HTML pages from known Online Resource indexes.
   *
   * @return array<int, string>
   */
  private function extractChildPageLinks(string $html, string $base_url, string $year): array {
    $dom = new \DOMDocument();
    libxml_use_internal_errors(TRUE);
    $dom->loadHTML($html, LIBXML_NOERROR | LIBXML_NOWARNING | LIBXML_NONET);
    libxml_clear_errors();

    $xpath = new \DOMXPath($dom);
    $content = $this->getContentRightNode($xpath);
    if (!$content instanceof \DOMElement) {
      return [];
    }

    $anchors = $xpath->query('.//a[@href]', $content);
    $pages = [];
    $seen = [];
    foreach ($anchors as $anchor) {
      if (!$anchor instanceof \DOMElement) {
        continue;
      }
      $href = trim($anchor->getAttribute('href'));
      if ($href === '' || str_starts_with($href, '#') || str_starts_with(strtolower($href), 'mailto:')) {
        continue;
      }
      $absolute = $this->absolutizeUrl($href, $base_url);
      if (!$this->isSameCensusYearUrl($absolute, $year)) {
        continue;
      }
      if (!$this->isLikelyHtmlPage($absolute)) {
        continue;
      }
      if (!$this->isRecognizedOnlineResourceUrl($absolute)) {
        continue;
      }
      if ($this->canonicalizePageUrl($absolute) === $this->canonicalizePageUrl($base_url)) {
        continue;
      }
      if (isset($seen[$absolute])) {
        continue;
      }
      $seen[$absolute] = TRUE;
      $pages[] = $absolute;
    }
    return $pages;
  }

  private function extractContentRightHtml(string $html): ?string {
    $dom = new \DOMDocument();
    libxml_use_internal_errors(TRUE);
    $dom->loadHTML($html, LIBXML_NOERROR | LIBXML_NOWARNING | LIBXML_NONET);
    libxml_clear_errors();
    $xpath = new \DOMXPath($dom);
    $content = $this->getContentRightNode($xpath);
    if (!$content instanceof \DOMElement) {
      return NULL;
    }
    return $dom->saveHTML($content) ?: NULL;
  }

  private function getContentRightNode(\DOMXPath $xpath): ?\DOMElement {
    $nodes = $xpath->query("//div[contains(concat(' ', normalize-space(@class), ' '), ' contentRight ')]");
    if ($nodes && $nodes->length > 0 && $nodes->item(0) instanceof \DOMElement) {
      return $nodes->item(0);
    }
    return NULL;
  }

  /**
   * Finds the section/caption context for an anchor inside div.contentRight.
   *
   * @return array{group:string,sectionPath:array<int,string>,rowLabel:?string,titleSource:string}
   */
  private function detectLinkContext(\DOMElement $anchor): array {
    $group = NULL;
    $row_label = NULL;
    $title_source = 'html_link_text';

    $table = $this->nearestAncestor($anchor, 'table');
    if ($table instanceof \DOMElement) {
      $caption = $this->firstText('.//caption[1]', $table);
      $table_class = ' ' . strtolower($table->getAttribute('class')) . ' ';
      if ($caption !== NULL) {
        $group = $caption;
        $title_source = str_contains($table_class, ' reports ') ? 'table_reports_caption' : 'table_caption';
      }

      $row = $this->nearestAncestor($anchor, 'tr');
      if ($row instanceof \DOMElement) {
        $row_label = $this->detectRowLabel($row, $anchor);
      }
    }

    if ($group === NULL) {
      $group = $this->nearestHeadingText($anchor) ?: 'Files';
    }

    return [
      'group' => $group,
      'sectionPath' => [$group],
      'rowLabel' => $row_label,
      'titleSource' => $title_source,
    ];
  }

  private function deriveFileLabel(\DOMElement $anchor, string $absolute_url, array $context): string {
    $anchor_label = $this->normalizer->cleanLabel($anchor->textContent ?? '');
    if ($anchor_label === 'Untitled file') {
      $anchor_label = '';
    }

    $extension = strtoupper(pathinfo(parse_url($absolute_url, PHP_URL_PATH) ?: '', PATHINFO_EXTENSION));
    $generic_labels = ['PDF', 'TXT', 'CSV', 'ZIP', 'XLS', 'XLSX', 'DOC', 'DOCX', 'RTF', 'XML', 'JSON', 'TEXT'];

    $row_label = isset($context['rowLabel']) ? $this->normalizer->cleanLabel((string) $context['rowLabel']) : '';
    if ($row_label !== '' && $row_label !== 'Untitled file' && in_array(strtoupper($anchor_label), $generic_labels, TRUE)) {
      return $row_label . ' - ' . $extension;
    }

    if ($anchor_label !== '') {
      return $anchor_label;
    }

    if ($row_label !== '' && $row_label !== 'Untitled file') {
      return $row_label . ' - ' . $extension;
    }

    return $this->normalizer->readableLabel($absolute_url);
  }

  /**
   * Returns the base URI for a page manifest.
   */
  private function pageBaseUri(string $url, string $year, string $base_public_uri): string {
    $path = rawurldecode(parse_url($url, PHP_URL_PATH) ?: '');
    $route = $this->routeInfoFromPath($path, NULL);

    if ($route['route'] !== 'default') {
      return $base_public_uri . '/' . $route['basePath'];
    }

    $relative_page_path = $this->relativePathAfterYear($url, $year);
    $normalized_page_path = $this->normalizer->normalizeDirectoryPath($relative_page_path);
    return $base_public_uri . ($normalized_page_path !== '' ? '/' . $normalized_page_path : '');
  }

  /**
   * Calculates final local folder placement for one downloaded file.
   *
   * @param array<string, mixed> $link
   *
   * @return array{targetDir:string,groupLabel:string,sectionPath:string,rawSectionPath:array<int,string>,routeRule:string,filenameLabel?:string}
   */
  private function routeAwarePlacement(string $page_url, string $source_url, string $label, array $link, string $year, string $page_public_uri): array {
    $source_path = rawurldecode(parse_url($source_url, PHP_URL_PATH) ?: '');
    $page_path = rawurldecode(parse_url($page_url, PHP_URL_PATH) ?: '');
    $route = $this->routeInfoFromPath($source_path, $label);
    if ($route['route'] === 'default') {
      $route = $this->routeInfoFromPath($page_path, $label);
    }

    if ($route['route'] !== 'default') {
      $target_dir = 'public://agcensus/' . $year . '/' . $route['basePath'];
      $group_label = $route['groupLabel'];
      $raw = $route['rawSectionPath'];
      return [
        'targetDir' => $target_dir,
        'groupLabel' => $group_label,
        'sectionPath' => $route['basePath'],
        'rawSectionPath' => $raw,
        'routeRule' => $route['route'],
        'filenameLabel' => $route['filenameLabel'] ?? $label,
      ];
    }

    $group_label = $this->normalizer->cleanLabel($link['group'] ?? 'Files');
    $section_path = $this->normalizer->normalizePathParts($link['sectionPath'] ?? [$group_label]);
    return [
      'targetDir' => $page_public_uri . ($section_path !== '' ? '/' . $section_path : '/files'),
      'groupLabel' => $group_label,
      'sectionPath' => $section_path,
      'rawSectionPath' => $link['sectionPath'] ?? [$group_label],
      'routeRule' => 'default_section',
      'filenameLabel' => $label,
    ];
  }

  /**
   * Reads a NASS URL path and converts known Online Resource routes to local paths.
   *
   * @return array{route:string,basePath:string,groupLabel:string,rawSectionPath:array<int,string>,filenameLabel?:string}
   */
  private function routeInfoFromPath(string $path, ?string $label): array {
    $normalized_path = str_replace('\\', '/', $path);
    $parts = array_values(array_filter(explode('/', trim($normalized_path, '/')), static fn($part) => $part !== ''));
    $decoded_parts = array_map(static fn($part) => rawurldecode($part), $parts);

    $index_of = static function (array $parts, string $needle): ?int {
      foreach ($parts as $i => $part) {
        if (strcasecmp($part, $needle) === 0) {
          return $i;
        }
      }
      return NULL;
    };

    $online_idx = $index_of($decoded_parts, 'Online_Resources');
    $full_report_idx = $index_of($decoded_parts, 'Full_Report');

    if ($online_idx !== NULL && isset($decoded_parts[$online_idx + 1])) {
      $resource = $decoded_parts[$online_idx + 1];
      $after = array_slice($decoded_parts, $online_idx + 2);
      $after = array_values(array_filter($after, static fn($part) => !in_array(strtolower($part), ['index.php', 'index.html', 'index.htm'], TRUE)));
      $path_parts = $this->nonFileRouteParts($after);
      $file_part = $this->firstDownloadRoutePart($after);

      if (strcasecmp($resource, 'Watersheds') === 0) {
        return [
          'route' => 'watershed',
          'basePath' => 'watershed',
          'groupLabel' => 'Watershed',
          'rawSectionPath' => ['Watershed'],
        ];
      }

      if (strcasecmp($resource, 'County_Profiles') === 0) {
        if ($label === NULL && $path_parts === [] && $file_part === NULL) {
          return [
            'route' => 'county_profiles_index',
            'basePath' => 'county_profiles',
            'groupLabel' => 'County Profiles',
            'rawSectionPath' => ['County Profiles'],
          ];
        }
        // Flatten single county profile PDFs into the state folder:
        // state/alabama/county_autauga.pdf. The cp01001.pdf source filename is
        // retained in the manifest, but it is never used as a folder name.
        $state = $path_parts[0] ?? $this->stateNameFromFilePart($file_part) ?? $this->stateNameFromLabel($label);
        $county = $this->countyNameFromLabel($label);
        $base = 'state/' . $this->normalizer->slug($state ?: 'unknown_state');
        $raw = ['State', $state ?: 'Unknown State', 'County Profiles'];
        $group = ($state ?: 'Unknown State') . ' County Profiles';
        $filename_label = $county && !$this->isStatewideLabel($label) ? 'county ' . $county : ($state ?: 'state') . ' county profiles';
        return [
          'route' => 'county_profiles',
          'basePath' => $base,
          'groupLabel' => $group,
          'rawSectionPath' => $raw,
          'filenameLabel' => $filename_label,
        ];
      }

      if (strcasecmp($resource, 'Congressional_District_Profiles') === 0) {
        if ($label === NULL && $path_parts === [] && $file_part === NULL) {
          return [
            'route' => 'congressional_district_profiles_index',
            'basePath' => 'congressional_district_profiles',
            'groupLabel' => 'Congressional District Profiles',
            'rawSectionPath' => ['Congressional District Profiles'],
          ];
        }
        // Flatten single district profile PDFs into the state folder:
        // state/alabama/district_1st.pdf. The cd0101.pdf source filename is
        // retained in the manifest, but it is never used as a folder name.
        $state = $path_parts[0] ?? $this->stateNameFromFilePart($file_part) ?? $this->stateNameFromLabel($label);
        $district = $this->districtNameFromLabel($label) ?? $this->districtNameFromFilePart($file_part) ?? $this->districtNameFromPartsAndLabel($path_parts, $label);
        $base = 'state/' . $this->normalizer->slug($state ?: 'unknown_state');
        $raw = ['State', $state ?: 'Unknown State', 'Congressional District Profiles'];
        $filename_label = $district !== NULL ? 'district ' . $this->districtFilenamePart($district) : 'congressional district profiles';
        return [
          'route' => 'congressional_district_profiles',
          'basePath' => $base,
          'groupLabel' => ($state ?: 'Unknown State') . ' Congressional District Profiles',
          'rawSectionPath' => $raw,
          'filenameLabel' => $filename_label,
        ];
      }

      if (strcasecmp($resource, 'Typology') === 0) {
        if ($label === NULL && $path_parts === [] && $file_part === NULL) {
          return [
            'route' => 'typology_index',
            'basePath' => 'typology',
            'groupLabel' => 'Typology',
            'rawSectionPath' => ['Typology'],
          ];
        }
        $state = $path_parts[0] ?? $this->stateNameFromFilePart($file_part) ?? $this->stateNameFromLabel($label);
        return [
          'route' => 'typology',
          'basePath' => 'state/' . $this->normalizer->slug($state ?: 'united_states'),
          'groupLabel' => ($state ?: 'United States') . ' Typology',
          'rawSectionPath' => ['State', $state ?: 'United States', 'Typology'],
          'filenameLabel' => 'typology',
        ];
      }

      if (strcasecmp($resource, 'Rankings_of_Market_Value') === 0) {
        if ($label === NULL && $path_parts === [] && $file_part === NULL) {
          return [
            'route' => 'market_value_rankings_index',
            'basePath' => 'market_value',
            'groupLabel' => 'Market Value Rankings',
            'rawSectionPath' => ['Market Value'],
          ];
        }
        $state = $path_parts[0] ?? $this->stateNameFromFilePart($file_part) ?? $this->stateNameFromLabel($label);
        $district = $this->districtNameFromLabel($label) ?? $this->districtNameFromFilePart($file_part) ?? $this->districtNameFromPartsAndLabel($path_parts, $label);
        $base = 'state/' . $this->normalizer->slug($state ?: 'united_states');
        $raw = ['State', $state ?: 'United States', 'Market Value'];
        // Statewide market value files stay flat as market_value.pdf. If the
        // source exposes multiple district/item files, keep them grouped in
        // state/[state]/market_value/.
        if ($district !== NULL) {
          return [
            'route' => 'market_value_rankings',
            'basePath' => $base . '/market_value',
            'groupLabel' => ($state ?: 'United States') . ' Market Value Rankings',
            'rawSectionPath' => $raw,
            'filenameLabel' => 'market value ' . $this->districtFilenamePart($district),
          ];
        }
        return [
          'route' => 'market_value_rankings',
          'basePath' => $base,
          'groupLabel' => ($state ?: 'United States') . ' Market Value Rankings',
          'rawSectionPath' => $raw,
          'filenameLabel' => 'market value',
        ];
      }

      if (strcasecmp($resource, 'Congressional_District_Rankings') === 0) {
        if ($label === NULL && $path_parts === [] && $file_part === NULL) {
          return [
            'route' => 'congressional_district_rankings_index',
            'basePath' => 'district_rankings',
            'groupLabel' => 'Congressional District Rankings',
            'rawSectionPath' => ['District Rankings'],
          ];
        }
        $state = $path_parts[0] ?? $this->stateNameFromFilePart($file_part) ?? $this->stateNameFromLabel($label);
        $district = $this->districtNameFromLabel($label) ?? $this->districtNameFromFilePart($file_part) ?? $this->districtNameFromPartsAndLabel($path_parts, $label);
        $base = 'state/' . $this->normalizer->slug($state ?: 'united_states') . '/district_rankings';
        $raw = ['State', $state ?: 'United States', 'District Rankings'];
        $filename_label = $district !== NULL ? 'district ' . $this->districtFilenamePart($district) . ' rankings' : ($label ?: 'district rankings');
        return [
          'route' => 'congressional_district_rankings',
          'basePath' => $base,
          'groupLabel' => ($state ?: 'Congressional District') . ' District Rankings',
          'rawSectionPath' => $raw,
          'filenameLabel' => $filename_label,
        ];
      }

      if (strcasecmp($resource, 'Hemp') === 0) {
        if ($label === NULL && $path_parts === [] && $file_part === NULL) {
          return [
            'route' => 'hemp_index',
            'basePath' => 'hemp',
            'groupLabel' => 'Hemp',
            'rawSectionPath' => ['Hemp'],
          ];
        }
        $state = $path_parts[0] ?? $this->stateNameFromFilePart($file_part) ?? $this->stateNameFromLabel($label);
        return [
          'route' => 'hemp',
          'basePath' => 'state/' . $this->normalizer->slug($state ?: 'united_states'),
          'groupLabel' => ($state ?: 'United States') . ' Hemp',
          'rawSectionPath' => ['State', $state ?: 'United States', 'Hemp'],
          'filenameLabel' => 'hemp',
        ];
      }
    }

    if ($full_report_idx !== NULL && isset($decoded_parts[$full_report_idx + 1])) {
      $resource = $decoded_parts[$full_report_idx + 1];
      if (strcasecmp($resource, 'Volume_1,_Chapter_1_State_Level') === 0 && isset($decoded_parts[$full_report_idx + 2])) {
        $state = $decoded_parts[$full_report_idx + 2];
        return [
          'route' => 'state_level_chapter_1',
          'basePath' => 'state/' . $this->normalizer->slug($state),
          'groupLabel' => $state . ' State Level Chapter 1',
          'rawSectionPath' => ['State', $state],
        ];
      }
    }

    return [
      'route' => 'default',
      'basePath' => '',
      'groupLabel' => 'Files',
      'rawSectionPath' => ['Files'],
    ];
  }

  /**
   * @param array<int, string> $parts
   *
   * @return array<int, string>
   */
  private function nonFileRouteParts(array $parts): array {
    $clean = [];
    foreach ($parts as $part) {
      if (!$this->isDownloadRoutePart($part)) {
        $clean[] = $part;
      }
    }
    return $clean;
  }

  /**
   * @param array<int, string> $parts
   */
  private function firstDownloadRoutePart(array $parts): ?string {
    foreach ($parts as $part) {
      if ($this->isDownloadRoutePart($part)) {
        return $part;
      }
    }
    return NULL;
  }

  private function isDownloadRoutePart(string $part): bool {
    $extension = strtolower(pathinfo($part, PATHINFO_EXTENSION));
    return in_array($extension, self::DOWNLOAD_EXTENSIONS, TRUE);
  }

  private function stateNameFromFilePart(?string $file_part): ?string {
    if ($file_part === NULL) {
      return NULL;
    }
    $name = strtolower(pathinfo($file_part, PATHINFO_FILENAME));
    if (preg_match('/^(cp|cd|mv|rank|ranking|hemp|typology)?([0-9]{2})[0-9]*/', $name, $matches)) {
      return self::STATE_FIPS[$matches[2]] ?? NULL;
    }
    return NULL;
  }

  private function districtNameFromFilePart(?string $file_part): ?string {
    if ($file_part === NULL) {
      return NULL;
    }
    $name = strtolower(pathinfo($file_part, PATHINFO_FILENAME));
    if (preg_match('/^cd([0-9]{2})([0-9]{2})/', $name, $matches)) {
      return 'district_' . str_pad($matches[2], 2, '0', STR_PAD_LEFT);
    }
    if (preg_match('/(?:district|dist|cd)[_\-]?([0-9]{1,2})/i', $name, $matches)) {
      return 'district_' . str_pad($matches[1], 2, '0', STR_PAD_LEFT);
    }
    return NULL;
  }

  private function stateNameFromLabel(?string $label): ?string {
    if ($label === NULL) {
      return NULL;
    }
    $label = $this->normalizer->cleanLabel($label);
    $label = preg_replace('/\s+-\s+(PDF|TXT|CSV|XLSX?|ZIP|DOCX?|RTF|XML|JSON)$/i', '', $label) ?? $label;
    $label = preg_replace('/\.(pdf|txt|csv|xlsx?|zip|docx?|rtf|xml|json)$/i', '', $label) ?? $label;
    $label = trim($label);
    return $label !== '' && $label !== 'Untitled file' ? $label : NULL;
  }

  private function countyNameFromLabel(?string $label): ?string {
    if ($label === NULL || $this->isStatewideLabel($label)) {
      return NULL;
    }
    $label = $this->stateNameFromLabel($label);
    if ($label === NULL) {
      return NULL;
    }
    $label = preg_replace('/\s+County$/i', '', $label) ?? $label;
    return trim($label) !== '' ? trim($label) : NULL;
  }

  private function isStatewideLabel(?string $label): bool {
    if ($label === NULL) {
      return FALSE;
    }
    return (bool) preg_match('/statewide|state\s+summary|summary/i', $label);
  }

  private function districtNameFromLabel(?string $label): ?string {
    if ($label === NULL) {
      return NULL;
    }
    $clean = $this->normalizer->cleanLabel($label);
    $clean = preg_replace('/\s+-\s+(PDF|TXT|CSV|XLSX?|ZIP|DOCX?|RTF|XML|JSON)$/i', '', $clean) ?? $clean;
    if (preg_match('/\b([0-9]{1,2})(st|nd|rd|th)\b/i', $clean, $matches)) {
      return strtolower($matches[1] . $matches[2]);
    }
    if (preg_match('/\bdistrict\s+([0-9]{1,2})\b/i', $clean, $matches)) {
      return $this->ordinal((int) $matches[1]);
    }
    return NULL;
  }

  private function districtFilenamePart(string $district): string {
    $district = strtolower($this->normalizer->cleanLabel($district));
    if (preg_match('/([0-9]{1,2})(st|nd|rd|th)/', $district, $matches)) {
      return $matches[1] . $matches[2];
    }
    if (preg_match('/district[_\s-]*([0-9]{1,2})/', $district, $matches)) {
      return $this->ordinal((int) $matches[1]);
    }
    if (preg_match('/^([0-9]{1,2})$/', $district, $matches)) {
      return $this->ordinal((int) $matches[1]);
    }
    return $district;
  }

  private function ordinal(int $number): string {
    $mod100 = $number % 100;
    if ($mod100 >= 11 && $mod100 <= 13) {
      return $number . 'th';
    }
    return $number . match ($number % 10) {
      1 => 'st',
      2 => 'nd',
      3 => 'rd',
      default => 'th',
    };
  }

  /**
   * @param array<int, string> $after
   */
  private function districtNameFromPartsAndLabel(array $after, ?string $label): ?string {
    foreach ($after as $part) {
      $candidate = pathinfo($part, PATHINFO_FILENAME);
      if (preg_match('/(district|cd|congressional|^[0-9]{1,2}(st|nd|rd|th)?$)/i', $candidate)) {
        return $this->districtLabel($candidate);
      }
    }
    if ($label !== NULL && preg_match('/\b([0-9]{1,2})(st|nd|rd|th)?\b/i', $label, $matches)) {
      return $this->districtLabel($matches[0]);
    }
    return NULL;
  }

  private function districtLabel(string $value): string {
    $value = $this->normalizer->cleanLabel($value);
    if (preg_match('/\b([0-9]{1,2})(st|nd|rd|th)?\b/i', $value, $matches)) {
      return 'district_' . str_pad($matches[1], 2, '0', STR_PAD_LEFT);
    }
    return $value;
  }

  private function shouldCrawlChildLinks(string $url): bool {
    $path = rawurldecode(parse_url($url, PHP_URL_PATH) ?: '');
    foreach (self::CRAWLABLE_ROUTE_MARKERS as $marker) {
      if (str_contains($path, $marker)) {
        return TRUE;
      }
    }
    return FALSE;
  }

  private function isRecognizedOnlineResourceUrl(string $url): bool {
    $path = rawurldecode(parse_url($url, PHP_URL_PATH) ?: '');
    foreach (self::CRAWLABLE_ROUTE_MARKERS as $marker) {
      if (str_contains($path, $marker)) {
        return TRUE;
      }
    }
    return FALSE;
  }

  private function isLikelyHtmlPage(string $url): bool {
    $path = parse_url($url, PHP_URL_PATH) ?: '';
    $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
    return $extension === '' || in_array($extension, ['php', 'html', 'htm'], TRUE) || str_ends_with($path, '/');
  }

  private function isSameCensusYearUrl(string $url, string $year): bool {
    $host = parse_url($url, PHP_URL_HOST) ?: '';
    if (!str_contains(strtolower($host), 'nass.usda.gov')) {
      return FALSE;
    }
    $path = rawurldecode(parse_url($url, PHP_URL_PATH) ?: '');
    return str_contains($path, '/AgCensus/' . $year . '/');
  }

  private function detectRowLabel(\DOMElement $row, \DOMElement $anchor): ?string {
    $cells = [];
    foreach ($row->childNodes as $child) {
      if ($child instanceof \DOMElement && in_array(strtolower($child->nodeName), ['th', 'td'], TRUE)) {
        $cells[] = $child;
      }
    }

    foreach ($cells as $cell) {
      if ($this->nodeContains($cell, $anchor)) {
        $label = $this->textBeforeAnchor($cell, $anchor);
        if ($label !== NULL) {
          return $label;
        }
        continue;
      }

      $text = $this->cellTextWithoutDownloadLabels($cell);
      if ($text !== NULL) {
        return $text;
      }
    }

    return $this->cellTextWithoutDownloadLabels($row);
  }

  private function cellTextWithoutDownloadLabels(\DOMElement $node): ?string {
    $clone = $node->cloneNode(TRUE);
    if (!$clone instanceof \DOMElement) {
      return NULL;
    }
    $this->removeDownloadAnchors($clone);
    $text = $this->normalizer->cleanLabel($clone->textContent ?? '');
    $text = preg_replace('/\b(TXT|PDF|CSV|ZIP|XLSX?|DOCX?|RTF|XML|JSON|TEXT)\b/i', '', $text) ?? $text;
    $text = trim(preg_replace('/\s*[\|\-–—,:;]+\s*$/', '', $text) ?? $text);
    $text = trim(preg_replace('/\s+/', ' ', $text) ?? $text);
    return ($text !== '' && $text !== 'Untitled file') ? $text : NULL;
  }

  private function removeDownloadAnchors(\DOMNode $node): void {
    $remove = [];
    foreach ($node->childNodes as $child) {
      if ($child instanceof \DOMElement && strtolower($child->nodeName) === 'a') {
        $label = strtoupper(trim($child->textContent ?? ''));
        if (in_array($label, ['PDF', 'TXT', 'CSV', 'ZIP', 'XLS', 'XLSX', 'DOC', 'DOCX', 'RTF', 'XML', 'JSON', 'TEXT'], TRUE)) {
          $remove[] = $child;
          continue;
        }
      }
      $this->removeDownloadAnchors($child);
    }
    foreach ($remove as $child) {
      $child->parentNode?->removeChild($child);
    }
  }

  private function textBeforeAnchor(\DOMElement $container, \DOMElement $anchor): ?string {
    $parts = [];
    foreach ($container->childNodes as $child) {
      if ($child->isSameNode($anchor)) {
        break;
      }
      if ($this->nodeContains($child, $anchor)) {
        break;
      }
      $parts[] = $child->textContent ?? '';
    }
    $text = $this->normalizer->cleanLabel(implode(' ', $parts));
    $text = trim(preg_replace('/\s*[\|\-–—,:;]+\s*$/', '', $text) ?? $text);
    return ($text !== '' && $text !== 'Untitled file') ? $text : NULL;
  }

  private function nodeContains(\DOMNode $container, \DOMNode $target): bool {
    if ($container->isSameNode($target)) {
      return TRUE;
    }
    foreach ($container->childNodes as $child) {
      if ($this->nodeContains($child, $target)) {
        return TRUE;
      }
    }
    return FALSE;
  }

  private function nearestAncestor(\DOMElement $node, string $tag): ?\DOMElement {
    $tag = strtolower($tag);
    $current = $node->parentNode;
    while ($current instanceof \DOMNode) {
      if ($current instanceof \DOMElement && strtolower($current->nodeName) === $tag) {
        return $current;
      }
      $current = $current->parentNode;
    }
    return NULL;
  }

  private function firstText(string $query, \DOMElement $context): ?string {
    $xpath = new \DOMXPath($context->ownerDocument);
    $result = $xpath->query($query, $context);
    if ($result && $result->length > 0) {
      $text = $this->normalizer->cleanLabel($result->item(0)->textContent ?? '');
      return ($text !== '' && $text !== 'Untitled file') ? $text : NULL;
    }
    return NULL;
  }

  private function nearestHeadingText(\DOMElement $anchor): ?string {
    $node = $anchor;
    while ($node instanceof \DOMNode && $node->parentNode) {
      $sibling = $node->previousSibling;
      while ($sibling instanceof \DOMNode) {
        if ($sibling instanceof \DOMElement) {
          $name = strtolower($sibling->nodeName);
          if (in_array($name, ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'caption', 'strong', 'b'], TRUE)) {
            $text = $this->normalizer->cleanLabel($sibling->textContent ?? '');
            if ($text !== '' && $text !== 'Untitled file') {
              return $text;
            }
          }
        }
        $sibling = $sibling->previousSibling;
      }
      $node = $node->parentNode;
    }
    return NULL;
  }

  private function extractPageTitle(string $html): ?string {
    $dom = new \DOMDocument();
    libxml_use_internal_errors(TRUE);
    $dom->loadHTML($html, LIBXML_NOERROR | LIBXML_NOWARNING | LIBXML_NONET);
    libxml_clear_errors();
    $xpath = new \DOMXPath($dom);

    foreach (['//h1', '//h2', '//h3', '//h4', '//title'] as $query) {
      $nodes = $xpath->query($query);
      if ($nodes && $nodes->length > 0) {
        $title = $this->normalizer->cleanLabel($nodes->item(0)->textContent ?? '');
        if ($title !== '' && $title !== 'Untitled file') {
          return $title;
        }
      }
    }
    return NULL;
  }

  private function detectPdfTitle(string $body): ?string {
    $sample = substr($body, 0, 200000);
    if (preg_match('/\/Title\s*\(([^\)]{3,200})\)/', $sample, $matches)) {
      $title = trim(str_replace(['\\(', '\\)'], ['(', ')'], $matches[1]));
      return $title !== '' ? $title : NULL;
    }
    return NULL;
  }

  private function writeJson(string $uri, array $data): void {
    $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    if ($json === FALSE) {
      throw new \RuntimeException('Unable to encode JSON for ' . $uri);
    }
    $this->fileSystem->saveData($json . PHP_EOL, $uri, FileSystemInterface::EXISTS_REPLACE);
  }

  private function updateYearManifest(string $base_public_uri, string $year, string $source_url, string $page_manifest_uri, string $page_title): void {
    $year_manifest_uri = $base_public_uri . '/manifest.json';
    $manifest = [
      'schema' => 'https://nass.local/schema/census-year-manifest-v1.json',
      'version' => '1.4',
      'year' => $year,
      'generatedAt' => gmdate('c', $this->time->getRequestTime()),
      'pages' => [],
    ];

    $realpath = $this->fileSystem->realpath($year_manifest_uri);
    if ($realpath && file_exists($realpath)) {
      $existing = json_decode(file_get_contents($realpath) ?: '', TRUE);
      if (is_array($existing)) {
        $manifest = $existing + $manifest;
        $manifest['pages'] = $existing['pages'] ?? [];
      }
    }

    $page_id = $this->normalizer->slug($this->relativePathAfterYear($source_url, $year));
    $manifest['version'] = '1.4';
    $manifest['generatedAt'] = gmdate('c', $this->time->getRequestTime());
    $manifest['pages'][$page_id] = [
      'id' => $page_id,
      'title' => $page_title,
      'sourceUrl' => $source_url,
      'manifestUri' => $page_manifest_uri,
      'manifestPath' => $this->publicRelativePath($page_manifest_uri),
      'updatedAt' => gmdate('c', $this->time->getRequestTime()),
    ];
    ksort($manifest['pages']);
    $manifest['pages'] = array_values($manifest['pages']);
    $this->writeJson($year_manifest_uri, $manifest);
  }

  private function relativePathAfterYear(string $url, string $year): string {
    $path = parse_url($url, PHP_URL_PATH) ?: '';
    return $this->relativePathAfterYearPath(dirname($path), $year);
  }

  private function relativePathAfterYearPath(string $path, string $year): string {
    $path = trim(rawurldecode($path), '/');
    $needle = 'AgCensus/' . $year;
    $pos = stripos($path, $needle);
    if ($pos !== FALSE) {
      return trim(substr($path, $pos + strlen($needle)), '/');
    }
    $parts = explode('/', $path);
    $year_pos = array_search($year, $parts, TRUE);
    if ($year_pos !== FALSE) {
      return implode('/', array_slice($parts, $year_pos + 1));
    }
    return $path;
  }

  private function absolutizeUrl(string $href, string $base_url): string {
    if (preg_match('#^https?://#i', $href)) {
      return $href;
    }
    $base_parts = parse_url($base_url);
    $scheme = $base_parts['scheme'] ?? 'https';
    $host = $base_parts['host'] ?? '';
    $port = isset($base_parts['port']) ? ':' . $base_parts['port'] : '';

    if (str_starts_with($href, '//')) {
      return $scheme . ':' . $href;
    }
    if (str_starts_with($href, '/')) {
      return $scheme . '://' . $host . $port . $href;
    }

    $base_path = $base_parts['path'] ?? '/';
    $dir = rtrim(str_ends_with($base_path, '/') ? $base_path : dirname($base_path), '/');
    $path = $dir . '/' . $href;
    $segments = [];
    foreach (explode('/', $path) as $segment) {
      if ($segment === '' || $segment === '.') {
        continue;
      }
      if ($segment === '..') {
        array_pop($segments);
        continue;
      }
      $segments[] = $segment;
    }
    return $scheme . '://' . $host . $port . '/' . implode('/', $segments);
  }

  private function canonicalizePageUrl(string $url): string {
    $url = preg_replace('/#.*$/', '', $url) ?? $url;
    $url = preg_replace('/\?.*$/', '', $url) ?? $url;
    return rtrim($url, '/');
  }

  private function isValidUrl(string $url): bool {
    return (bool) filter_var($url, FILTER_VALIDATE_URL) && in_array(parse_url($url, PHP_URL_SCHEME), ['http', 'https'], TRUE);
  }

  private function publicRelativePath(string $uri): string {
    return preg_replace('#^public://#', '', $uri) ?? $uri;
  }

  private function guessDisplay(string $group_label): string {
    return str_contains(strtolower($group_label), 'table') ? 'dropdown' : 'links';
  }

  private function guessMimeType(string $source_url): string {
    $extension = strtolower(pathinfo(parse_url($source_url, PHP_URL_PATH) ?: '', PATHINFO_EXTENSION));
    return match ($extension) {
      'pdf' => 'application/pdf',
      'txt' => 'text/plain',
      'csv' => 'text/csv',
      'zip' => 'application/zip',
      'xls' => 'application/vnd.ms-excel',
      'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
      'doc' => 'application/msword',
      'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
      'json' => 'application/json',
      'xml' => 'application/xml',
      default => 'application/octet-stream',
    };
  }

  /**
   * @return array<string, string>
   */
  private function routeRulesSummary(): array {
    return [
      'Watersheds' => 'public://agcensus/[year]/watershed/[normalized-file]',
      'Volume_1_Chapter_1_State_Level/[State]' => 'public://agcensus/[year]/state/[state-name]/[normalized-file]',
      'County_Profiles/[State]' => 'public://agcensus/[year]/state/[state-name]/county_[county-name].pdf',
      'Congressional_District_Profiles' => 'public://agcensus/[year]/state/[state-name]/district_[ordinal].pdf',
      'Typology' => 'public://agcensus/[year]/state/[state-name]/typology.pdf',
      'Rankings_of_Market_Value' => 'public://agcensus/[year]/state/[state-name]/market_value.pdf for single statewide files, or public://agcensus/[year]/state/[state-name]/market_value/[normalized-file] for multi-file/district groups',
      'Congressional_District_Rankings' => 'public://agcensus/[year]/state/[state-name]/district_rankings/[normalized-file]',
      'Hemp' => 'public://agcensus/[year]/state/[state-name]/hemp.pdf',
    ];
  }

}
