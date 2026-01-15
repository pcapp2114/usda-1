<?php

namespace Drupal\views_block_area\Plugin\views\field;

use Drupal\Core\Form\FormStateInterface;
use Drupal\views\Plugin\views\field\FieldPluginBase;
use Drupal\views\ResultRow;
use Drupal\views_block_area\ViewsBlockCreationHelper;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Block field.
 *
 * @ingroup views_field_handlers
 *
 * @ViewsField("views_block_field")git
 */
class ViewsBlockField extends FieldPluginBase {

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
    $options = array_merge($options, $this->viewsBlockCreationHelper->defineOptions());
    $options['empty'] = ['default' => FALSE];

    return $options;
  }

  /**
   * {@inheritdoc}
   *
   * Needs to be implemented because of the parent interface.
   * In our case we don't need to override parent query, so it stays unchanged.
   */
  public function query(): void {
  }

  /**
   * {@inheritdoc}
   */
  public function buildOptionsForm(&$form, FormStateInterface $form_state): void {
    parent::buildOptionsForm($form, $form_state);
    $additional_options = $this->viewsBlockCreationHelper->buildOptionsForm($this->options);
    $form['empty'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Display even if view has no result'),
      '#description' => $this->t('Enabling this option shows the block even if no results available'),
      '#default_value' => $this->options['empty'],
      '#access' => TRUE,
    ];

    $form = array_merge($form, $additional_options);
  }

  /**
   * {@inheritdoc}
   */
  public function render(ResultRow $values) {
    return $this->viewsBlockCreationHelper->render($this->options);
  }

}
