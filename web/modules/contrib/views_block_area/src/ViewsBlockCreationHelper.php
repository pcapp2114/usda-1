<?php

namespace Drupal\views_block_area;

use Drupal\Core\Block\BlockManagerInterface;
use Drupal\Core\Entity\EntityRepositoryInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Render\RendererInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\StringTranslation\TranslationInterface;

/**
 * Contains common methods related to the view block creation.
 */
class ViewsBlockCreationHelper {
  use StringTranslationTrait;

  /**
   * The block plugin manager.
   *
   * @var \Drupal\Core\Block\BlockManagerInterface
   */
  protected BlockManagerInterface $blockManager;

  /**
   * The renderer.
   *
   * @var \Drupal\Core\Render\RendererInterface
   */
  protected RendererInterface $renderer;

  /**
   * The module handler.
   *
   * @var \Drupal\Core\Extension\ModuleHandlerInterface
   */
  protected ModuleHandlerInterface $moduleHandler;

  /**
   * The current user.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected AccountInterface $currentUser;

  /**
   * The entity repository.
   *
   * @var \Drupal\Core\Entity\EntityRepositoryInterface
   */
  protected EntityRepositoryInterface $entityRepository;

  /**
   * Constructs a new ViewsBlockCreationHelper instance.
   */
  public function __construct(
    BlockManagerInterface $block_manager,
    RendererInterface $renderer,
    ModuleHandlerInterface $module_handler,
    TranslationInterface $string_translation,
    AccountInterface $current_user,
    EntityRepositoryInterface $entity_repository = NULL,
  ) {
    $this->blockManager = $block_manager;
    $this->renderer = $renderer;
    $this->moduleHandler = $module_handler;
    $this->stringTranslation = $string_translation;
    $this->currentUser = $current_user;
    $this->entityRepository = $entity_repository;
  }

  /**
   * Returns user-friendly admin summary string.
   */
  public function adminSummary($block_id) {
    $block = $this->getBlock($block_id);
    return $block ? $block->label() : $this->t('Block');
  }

  /**
   * Returns additional options for rendered block form.
   */
  public function defineOptions(): array {
    return [
      'block_id' => ['default' => NULL],
      'block_title' => ['default' => NULL],
      'hide_label' => ['default' => FALSE],
    ];
  }

  /**
   * Builds options form.
   */
  public function buildOptionsForm($block_options): array {

    $options = [];

    $definitions = $this->getBlockDefinitions();
    foreach ($definitions as $id => $definition) {
      // If allowed plugin ids are set then check that this block should be
      // included.
      $category = (string) $definition['category'];
      $options[$category][$id] = $definition['admin_label'];
    }

    $form['block_id'] = [
      '#type' => 'select',
      '#title' => $this->t('Block'),
      '#options' => $options,
      '#empty_option' => $this->t('Please select'),
      '#default_value' => $block_options['block_id'],
    ];

    $form['block_title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Title'),
      '#default_value' => $block_options['block_title'],
    ];

    $form['hide_label'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Hide block label'),
      '#description' => $this->t('Enabling this option hides the block label'),
      '#default_value' => $block_options['hide_label'],
    ];

    return $form;
  }

  /**
   * Get sorted listed of supported block definitions.
   *
   * @return array
   *   An associative array of supported block definitions.
   */
  public function getBlockDefinitions(): array {
    $definitions = $this->blockManager->getSortedDefinitions();
    $block_definitions = [];
    foreach ($definitions as $plugin_id => $definition) {
      // Context aware plugins are not currently supported.
      // Core and component plugins can be context-aware
      // https://www.drupal.org/node/1938688
      // @see \Drupal\ctools\Plugin\Block\EntityView
      if (!empty($definition['context'])) {
        continue;
      }

      $block_definitions[$plugin_id] = $definition;
    }
    return $block_definitions;
  }

  /**
   * Renders selected block.
   */
  public function render($block_options) {

    $block_instance = $this->getBlock($block_options['block_id']);

    // Make sure the block exists and is accessible.
    if (!$block_instance || !$block_instance->access($this->currentUser)) {
      return NULL;
    }

    $block_build = $block_instance->build();

    if (!empty($block_options['empty'])
      || !empty($block_build['#view']->result)
      || !empty($block_build['#markup'])) {
      // Set the block label visibility.
      // For custom blocks block labels should be always hidden.
      $block_instance->setConfigurationValue(
        'label_display',
        empty($block_build['#view']) ? FALSE : !$block_options['hide_label'],
      );

      $base_id = $block_instance->getBaseId();

      // @see \Drupal\block\BlockViewBuilder::buildPreRenderableBlock
      // @see template_preprocess_block()
      $element = [
        '#theme' => 'block',
        '#attributes' => [],
        '#configuration' => $block_instance->getConfiguration(),
        '#plugin_id' => $block_instance->getPluginId(),
        '#base_plugin_id' => $base_id,
        '#derivative_plugin_id' => $block_instance->getDerivativeId(),
        '#id' => $block_instance->getPluginId(),
        'content' => $block_build,
      ];

      // Set block title.
      if (!$block_options['hide_label']
        && !empty($block_options['block_title'])) {
        if (!empty($element['content']['#title'])) {
          $element['content']['#title'] = $block_options['block_title'];
        }
        elseif (!empty($element['content']['#markup'])) {
          $element['content']['#markup'] = '<h2>' . $block_options['block_title'] . '</h2>' . $element['content']['#markup'];
        }
      }
      elseif ($block_options['hide_label']) {
        unset($element['content']['#title']);
      }

      // Allow alter hooks to modify the block contents.
      $this->moduleHandler->alter(['block_view', "block_view_$base_id"], $element, $block_instance);

      $renderer = $this->renderer;
      $renderer->addCacheableDependency($element, $block_instance);
      return $element;
    }
  }

  /**
   * Returns block instance.
   */
  public function getBlock($block_id): ?object {
    $block_instance = $this->blockManager->createInstance($block_id, []);
    $plugin_definition = $block_instance->getPluginDefinition();

    // Don't return broken block plugin instances.
    if ($plugin_definition['id'] === 'broken') {
      return NULL;
    }

    // Don't return broken block content instances.
    if ($plugin_definition['id'] === 'block_content') {
      $uuid = $block_instance->getDerivativeId();
      if (!$this->entityRepository->loadEntityByUuid('block_content', $uuid)) {
        return NULL;
      }
    }

    return $block_instance;
  }

}
