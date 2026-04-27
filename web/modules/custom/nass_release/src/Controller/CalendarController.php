<?php

namespace Drupal\nass_release\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\nass_release\Service\ReleaseRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

final class CalendarController extends ControllerBase {

  public function __construct(private readonly ReleaseRepository $repository) {}

  public static function create(ContainerInterface $container): self {
    return new self($container->get('nass_release.release_repository'));
  }

  public function page(Request $request): array {
    $tz = new \DateTimeZone('America/New_York');
    $month = $request->query->get('month') ?: (new \DateTimeImmutable('now', $tz))->format('Y-m');
    if (!preg_match('/^\d{4}-\d{2}$/', $month)) {
      $month = (new \DateTimeImmutable('now', $tz))->format('Y-m');
    }

    $first = new \DateTimeImmutable($month . '-01 00:00:00', $tz);
    $grid_start = $first->modify('monday this week');
    $last = $first->modify('last day of this month');
    $grid_end = $last->modify('friday this week')->setTime(23, 59, 59);

    $records = $this->repository->all([
      'from' => $grid_start->format('Y-m-d H:i:s'),
      'to' => $grid_end->format('Y-m-d H:i:s'),
    ]);

    $by_date = [];
    foreach ($records as $record) {
      $date = (new \DateTimeImmutable($record['release_datetime'], $tz))->format('Y-m-d');
      $by_date[$date][] = $record;
    }

    $weeks = [];
    $cursor = $grid_start;
    while ($cursor <= $grid_end) {
      $week = [];
      for ($i = 0; $i < 5; $i++) {
        $key = $cursor->format('Y-m-d');
        $week[] = [
          'day' => $cursor->format('j'),
          'date' => $key,
          'in_month' => $cursor->format('Y-m') === $first->format('Y-m'),
          'items' => $by_date[$key] ?? [],
        ];
        $cursor = $cursor->modify('+1 day');
      }
      $weeks[] = $week;
      $cursor = $cursor->modify('+2 days');
    }

    return [
      '#theme' => 'nass_release_calendar',
      '#month_label' => $first->format('F Y'),
      '#prev_month' => $first->modify('-1 month')->format('Y-m'),
      '#next_month' => $first->modify('+1 month')->format('Y-m'),
      '#weeks' => $weeks,
      '#attached' => ['library' => ['nass_release/nass_release']],
      '#cache' => ['max-age' => 60, 'contexts' => ['url.query_args:month']],
    ];
  }

}
