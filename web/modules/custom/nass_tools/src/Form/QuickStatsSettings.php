<?php

namespace Drupal\nass_tools\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides the NASS Quick Stats settings form.
 *
 * This class preserves the legacy nass_quick_stats.adminsettings config name so
 * existing code, routes, and exported configuration continue to work after the
 * merge into nass_tools.
 */
class QuickStatsSettings extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'nass_quick_stats.adminsettings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'nass_quick_stats_url_form';
  }

  /**
   * Builds the Quick Stats settings form.
   *
   * Defaults are defensive because the legacy config object may not exist after
   * uninstalling the old nass_quick_stats module and enabling nass_tools.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('nass_quick_stats.adminsettings');

    $textarea = $config->get('nass_quick_stats_textarea');
    if (!is_array($textarea)) {
      $textarea = [
        'value' => '',
        'format' => 'full_html',
      ];
    }

    $form['nass_quick_stats_url'] = [
      '#type' => 'url',
      '#title' => $this->t('Quick Stats API base URL'),
      '#description' => $this->t('Please enter the base URL of the Quick Stats API.'),
      '#default_value' => $config->get('nass_quick_stats_url') ?? '',
    ];

    $form['nass_quick_stats_key'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Quick Stats Security API Key'),
      '#description' => $this->t('This API Key is required for the module to connect to the Quick Stats API.'),
      '#default_value' => $config->get('nass_quick_stats_key') ?? '',
    ];

    $form['nass_quick_stats_disable'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Disable QuickStats'),
      '#description' => $this->t('Disable Quick Stats from rendering. The textarea below will continue to display.'),
      '#default_value' => (bool) ($config->get('nass_quick_stats_disable') ?? FALSE),
    ];

    $form['nass_quick_stats_disabled_msg'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Optional Quick Stats Disabled Message'),
      '#description' => $this->t('Use this to display a helpful message when Quick Stats is disabled. This only displays when the QuickStats disabled toggle is on.'),
      '#default_value' => $config->get('nass_quick_stats_disabled_msg') ?? '',
    ];

    $form['nass_quick_stats_textarea'] = [
      '#type' => 'text_format',
      '#title' => $this->t('Message'),
      '#format' => $textarea['format'] ?? 'full_html',
      '#description' => $this->t('Message display to customer contacts.'),
      '#default_value' => $textarea['value'] ?? '',
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * Saves the Quick Stats settings.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $this->config('nass_quick_stats.adminsettings')
      ->set('nass_quick_stats_url', $form_state->getValue('nass_quick_stats_url'))
      ->set('nass_quick_stats_disable', $form_state->getValue('nass_quick_stats_disable'))
      ->set('nass_quick_stats_disabled_msg', $form_state->getValue('nass_quick_stats_disabled_msg'))
      ->set('nass_quick_stats_key', $form_state->getValue('nass_quick_stats_key'))
      ->set('nass_quick_stats_textarea', $form_state->getValue('nass_quick_stats_textarea'))
      ->save();
  }

}
