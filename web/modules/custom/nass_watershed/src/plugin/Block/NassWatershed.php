<?php

namespace Drupal\nass_watershed\Plugin\Block;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * Provides a block with a simple text.
 *
 * @Block(
 *   id = "nass_watershed",
 *   admin_label = @Translation("NASS Watershed")
 * )
 */
class NassWatershed extends BlockBase {
  /**
   * {@inheritdoc}
   */
  public function build() {
    return [
      '#markup' => $this->t(''),
    ];
  }

  /**
   * {@inheritdoc}
   */
  protected function blockAccess(AccountInterface $account) {
    return AccessResult::allowedIfHasPermission($account, 'access content');
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $config = $this->getConfiguration();

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['nass_watershed'] = $form_state->getValue('nass_watershed');
  }
}