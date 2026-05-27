<?php

namespace Drupal\census\Commands;

use Consolidation\AnnotatedCommand\Attributes as CLI;
use Drupal\census\Service\CensusExporter;
use Drush\Commands\DrushCommands;

/**
 * Drush commands for the Census export test module.
 */
final class CensusCommands extends DrushCommands {

  public function __construct(private readonly CensusExporter $exporter) {
    parent::__construct();
  }

  /**
   * Scrapes one NASS Census page and downloads linked files locally.
   */
  #[CLI\Command(name: 'census_export', aliases: ['census:export'])]
  #[CLI\Option(name: 'url', description: 'Full source page URL to scrape, for example https://www.nass.usda.gov/Publications/AgCensus/2022/Full_Report/Volume_1,_Chapter_1_US/')]
  #[CLI\Option(name: 'year', description: 'Census year, for example 2022.')]
  #[CLI\Option(name: 'dry-run', description: 'Scrape and plan the export without downloading or writing files.')]
  #[CLI\Option(name: 'no-overwrite', description: 'Skip files that already exist locally.')]
  #[CLI\Usage(name: 'drush census_export --url=https://www.nass.usda.gov/Publications/AgCensus/2022/Full_Report/Volume_1,_Chapter_1_US/ --year=2022', description: 'Exports the 2022 Volume 1 Chapter 1 page into public://agcensus/2022/full_report/volume_1_chapter_1_us/.')]
  public function export(array $options = [
    'url' => NULL,
    'year' => NULL,
    'dry-run' => FALSE,
    'no-overwrite' => FALSE,
  ]): int {
    $url = (string) ($options['url'] ?? '');
    $year = (string) ($options['year'] ?? '');
    $dry_run = (bool) ($options['dry-run'] ?? FALSE);
    $overwrite = !((bool) ($options['no-overwrite'] ?? FALSE));

    if ($url === '' || $year === '') {
      $this->logger()->error('Both --url and --year are required.');
      return 1;
    }

    try {
      $summary = $this->exporter->export($url, $year, $dry_run, $overwrite);
    }
    catch (\Throwable $e) {
      $this->logger()->error($e->getMessage());
      return 1;
    }

    $this->io()->success('Census export completed.');
    $this->io()->definitionList(
      ['Source URL' => $summary['sourceUrl']],
      ['Year' => $summary['year']],
      ['Page title' => $summary['pageTitle']],
      ['Local base URI' => $summary['localBaseUri']],
      ['Page manifest' => $summary['pageManifestUri']],
      ['Report' => $summary['reportUri']],
      ['Links found' => (string) $summary['linksFound']],
      ['Child pages queued' => (string) ($summary['childPagesQueued'] ?? 0)],
      ['Pages processed' => (string) ($summary['pagesProcessed'] ?? 1)],
      ['Files downloaded' => (string) $summary['filesDownloaded']],
      ['Files skipped' => (string) $summary['filesSkipped']],
      ['Files failed' => (string) $summary['filesFailed']],
      ['Dry run' => $summary['dryRun'] ? 'yes' : 'no'],
    );

    if (!empty($summary['warnings'])) {
      $this->io()->warning(count($summary['warnings']) . ' warning(s) were recorded. Review migration-report.json.');
    }

    return $summary['filesFailed'] > 0 ? 1 : 0;
  }

}
