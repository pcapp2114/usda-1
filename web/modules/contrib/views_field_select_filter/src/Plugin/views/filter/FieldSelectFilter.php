<?php

namespace Drupal\views_field_select_filter\Plugin\views\filter;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\views\Plugin\views\filter\InOperator;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Filter handler which allows to search on multiple fields.
 *
 * @ingroup views_field_handlers
 *
 * @ViewsFilter("fieldselect")
 */
class FieldSelectFilter extends InOperator implements ContainerFactoryPluginInterface {

  /**
   * {@inheritdoc}
   */
  protected $valueFormType = 'select';

  /**
   * Creates an instance of ModerationStateFilter.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, protected LanguageManagerInterface $languageManager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('language_manager'),
    );
  }

  /**
   * {@inheritdoc}
   */
  protected function defineOptions() {
    $options = parent::defineOptions();
    $options['expose']['contains']['sort_values'] = ['default' => 0];
    $options['expose']['contains']['current_language'] = ['default' => 0];
    return $options;
  }

  /**
   * {@inheritDoc}
   */
  public function defaultExposeOptions() {
    parent::defaultExposeOptions();
    $this->options['expose']['sort_values'] = 0;
    $this->options['expose']['current_language'] = 0;
  }

  /**
   * {@inheritDoc}
   */
  public function buildExposeForm(&$form, FormStateInterface $form_state) {
    parent::buildExposeForm($form, $form_state);
    $form['expose']['sort_values'] = [
      '#type' => 'radios',
      '#title' => $this->t('Value order'),
      '#description' => $this->t('How to sort the values in the exposed form'),
      '#options' => [0 => $this->t('ASC'), 1 => $this->t('DESC')],
      '#default_value' => $this->options['expose']['sort_values'],
      '#weight' => 1,
    ];
    $form['expose']['current_language'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Options in current language only'),
      '#description' => $this->t('If checked, only show options in the current interface language'),
      '#default_value' => $this->options['expose']['current_language'],
      '#access' => count($this->languageManager->getLanguages()) > 1,
    ];
  }

  /**
   * {@inheritDoc}
   */
  public function operatorOptions($which = 'in') {
    return [];
  }

  /**
   * {@inheritDoc}
   */
  public function getValueOptions() {
    if (isset($this->valueOptions)) {
      return $this->valueOptions;
    }

    $allFilters = $this->displayHandler->getOption('filters');
    $connection = \Drupal::database();
    /** @var \Drupal\Core\Database\Query\Select $query */
    $query = $connection->select($this->table, 'ft')
      ->fields('ft', [$this->realField])
      ->distinct(TRUE);

    if ($this->options['expose']['current_language']) {
      $language = \Drupal::languageManager()->getCurrentLanguage();
      $query->condition('langcode', $language->getId());
    }

    $query->orderBy($this->realField, ($this->options['expose']['sort_values'] == '1') ? 'desc' : 'asc');
    if (!empty($allFilters['type']['value'])) {
      $types = array_keys($allFilters['type']['value']);
      $query->condition('bundle', $types, 'in');
    }
    $values = $query->execute()->fetchAllKeyed(0, 0);
    $this->valueOptions = $values;
  }

  /**
   * {@inheritdoc}
   */
  protected function valueForm(&$form, FormStateInterface $form_state) {
    // Only show the value form in the exposed form.
    // No support yet for limiting the number of selectable options:
    $form['value'] = [];

    // $this->buildOptionsForm($form, $form_state);
    if ($form_state->get('exposed')) {
      parent::valueForm($form, $form_state);
    }
  }

}
