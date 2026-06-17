<?php

namespace Drupal\nass_tools\Service;

use Drupal\Core\Config\ConfigFactoryInterface;
use GuzzleHttp\ClientInterface;
use Psr\Log\LoggerInterface;

final class ApiReleaseSource implements ReleaseSourceInterface {

  public function __construct(
    private readonly ConfigFactoryInterface $configFactory,
    private readonly ClientInterface $httpClient,
    private readonly ReleaseNormalizer $normalizer,
    private readonly LoggerInterface $logger,
  ) {}

  public function fetch(array $options = []): array {
    $config = $this->configFactory->get('nass_release.adminsettings');
    $endpoint = trim((string) $config->get('api_endpoint'));
    if ($endpoint === '') {
      return [];
    }

    try {
      $response = $this->httpClient->request('GET', $endpoint, [
        'timeout' => (int) ($config->get('api_timeout') ?: 10),
        'headers' => ['Accept' => 'application/json'],
      ]);
      $payload = json_decode((string) $response->getBody(), TRUE);
      $items = $payload['releases'] ?? $payload['items'] ?? (is_array($payload) ? $payload : []);
      $records = [];
      foreach ($items as $item) {
        if (!is_array($item)) {
          continue;
        }
        $record = $this->normalizer->normalize($item, 'api');
        if ($record) {
          $records[] = $record->toArray();
        }
      }
      return $this->filterArrays($records, $options);
    }
    catch (\Throwable $e) {
      $this->logger->error('NASS release API source failed: @message', ['@message' => $e->getMessage()]);
      return [];
    }
  }

  private function filterArrays(array $records, array $options): array {
    $from = !empty($options['from']) ? strtotime($options['from']) : NULL;
    $to = !empty($options['to']) ? strtotime($options['to']) : NULL;
    return array_values(array_filter($records, static function (array $record) use ($from, $to): bool {
      $ts = strtotime($record['release_datetime'] ?? '');
      if (!$ts) {
        return FALSE;
      }
      if ($from && $ts < $from) {
        return FALSE;
      }
      if ($to && $ts > $to) {
        return FALSE;
      }
      return TRUE;
    }));
  }

}
