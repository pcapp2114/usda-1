<?php

namespace Drupal\nass_tools\Plugin\Block;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Session\AccountInterface;

/**
 * Provides the NASS Today's Releases block.
 *
 * @Block(
 *   id = "nass_release_todays_release",
 *   admin_label = @Translation("NASS Release - Today's Release"),
 * )
 */
class NassReleaseToday extends BlockBase {
  public function build(): array {
    return [
      '#markup' => $this->t('Today\'s Release'),
      '#attached' => ['library' => ['nass_tools/nass_release']],
      '#cache' => ['max-age' => 0],
    ];
  }
  protected function blockAccess(AccountInterface $account): AccessResult {
    return AccessResult::allowedIfHasPermission($account, 'access content');
  }
}
