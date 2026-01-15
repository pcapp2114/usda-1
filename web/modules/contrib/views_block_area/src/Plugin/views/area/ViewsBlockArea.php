<?php

namespace Drupal\views_block_area\Plugin\views\area;

use Drupal\Core\Form\FormStateInterface;
use Drupal\views\Plugin\views\area\AreaPluginBase;
use Drupal\views_block_area\ViewsBlockCreationHelper;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides an area handler which renders a block entity in a certain view mode.
 *
 * @ingroup views_area_handlers
 *
 * @ViewsArea("views_block_area")
 */
class ViewsBlockArea extends AreaPluginBase {

  /**
   * The module handler.
   *
   * @var \Drupal\views_block_area\ViewsBlockCreationHelper
   */
  protected ViewsBlockCreationHelper $viewsBlockCreationHelper;

  /**
   * {@inheritdoc}
   */
  public static function create(
    ContainerInterface $container,
    array $configuration,
    $plugin_id,
    $plugin_definition,
  ) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('views_block_area.creation_helper'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    ViewsBlockCreationHelper $views_block_creation_helper,
  ) {
    $this->viewsBlockCreationHelper = $views_block_creation_helper;
    parent::__construct($configuration, $plugin_id, $plugin_definition);
  }

  /**
   * {@inheritdoc}
   */
  public function adminSummary() {
    return $this->viewsBlockCreationHelper->adminSummary($this->options['block_id']);
  }

  /**
   * {@inheritdoc}
   */
  protected function defineOptions(): array {
    $options = parent::defineOptions();
    return array_merge($options, $this->viewsBlockCreationHelper->defineOptions());
  }

  /**
   * {@inheritdoc}
   */
  public function buildOptionsForm(&$form, FormStateInterface $form_state): void {
    parent::buildOptionsForm($form, $form_state);
    $additional_options = $this->viewsBlockCreationHelper->buildOptionsForm($this->options);
    $form = array_merge($form, $additional_options);
  }

  /**
   * {@inheritdoc}
   */
  public function render($empty = FALSE): ?array {
    if (!$empty || $this->options['empty']) {
      return $this->viewsBlockCreationHelper->render($this->options);
    }
    return NULL;
  }

}
