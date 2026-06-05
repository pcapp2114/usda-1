<?php

namespace Drupal\nass_tools\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\nass_tools\Service\ReleaseRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

final class ReleaseJsonController extends ControllerBase {

  public function __construct(private readonly ReleaseRepository $repository) {}

  public static function create(ContainerInterface $container): self {
    return new self($container->get('nass_release.release_repository'));
  }

  public function current(): JsonResponse {
    return new JsonResponse($this->repository->today());
  }

  public function upcoming(Request $request): JsonResponse {
    $days = $request->query->getInt('days') ?: NULL;
    return new JsonResponse($this->repository->upcoming($days));
  }

  public function calendar(Request $request): JsonResponse {
    $days = $request->query->getInt('days') ?: NULL;
    return new JsonResponse($this->repository->calendar($days));
  }

  public function all(): JsonResponse {
    return new JsonResponse($this->repository->all());
  }

}
