<?php

namespace Drupal\nass_release\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\nass_release\Service\ReleaseRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

final class AjaxUpcomingController extends ControllerBase {

  public function __construct(private readonly ReleaseRepository $repository) {}

  public static function create(ContainerInterface $container): self {
    return new self($container->get('nass_release.release_repository'));
  }

  public function ajaxCallback(): JsonResponse {
    return new JsonResponse($this->repository->upcoming());
  }

}
