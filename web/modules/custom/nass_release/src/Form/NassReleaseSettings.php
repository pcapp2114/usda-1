<?php

namespace Drupal\nass_release\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

final class NassReleaseSettings extends ConfigFormBase {

  protected function getEditableConfigNames(): array {
    return ['nass_release.adminsettings'];
  }

  public function getFormId(): string {
    return 'nass_release_settings';
  }

  public function buildForm(array $form, FormStateInterface $form_state): array {
    $config = $this->config('nass_release.adminsettings');

    $form['display'] = [
      '#type' => 'details',
      '#title' => $this->t('Display settings'),
      '#open' => TRUE,
    ];
    $form['display']['nass_release_on_off_toggle'] = [
      '#type' => 'radios',
      '#title' => $this->t("Turn on Today's Releases"),
      '#default_value' => $config->get('nass_release_on_off_toggle') ?? 1,
      '#options' => [0 => $this->t('Off'), 1 => $this->t('On')],
    ];
    $form['display']['nass_release_disabled_message'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Release disabled message'),
      '#default_value' => $config->get('nass_release_disabled_message') ?: 'No releases are scheduled for today.',
    ];
    $form['display']['nass_release_host_url'] = [
      '#type' => 'url',
      '#title' => $this->t("Today's Release host URL"),
      '#default_value' => $config->get('nass_release_host_url') ?: 'https://release.nass.usda.gov/reports/',
      '#description' => $this->t('Base URL used when building Text/PDF/CSV links from release filenames.'),
    ];
    $textarea = $config->get('nass_release_textarea') ?: ['value' => '', 'format' => 'full_html'];
    $form['display']['nass_release_textarea'] = [
      '#type' => 'text_format',
      '#title' => $this->t('No release today message'),
      '#format' => $textarea['format'] ?? 'full_html',
      '#default_value' => $textarea['value'] ?? '',
    ];
    $textarea_top = $config->get('nass_release_textarea_page_top') ?: ['value' => '', 'format' => 'full_html'];
    $form['display']['nass_release_textarea_page_top'] = [
      '#type' => 'text_format',
      '#title' => $this->t("Today's Release page top content"),
      '#format' => $textarea_top['format'] ?? 'full_html',
      '#default_value' => $textarea_top['value'] ?? '',
    ];
    $textarea_bottom = $config->get('nass_release_textarea_page_bottom') ?: ['value' => '', 'format' => 'full_html'];
    $form['display']['nass_release_textarea_page_bottom'] = [
      '#type' => 'text_format',
      '#title' => $this->t("Today's Release page bottom content"),
      '#format' => $textarea_bottom['format'] ?? 'full_html',
      '#default_value' => $textarea_bottom['value'] ?? '',
    ];

    $form['sources'] = [
      '#type' => 'details',
      '#title' => $this->t('Release data sources'),
      '#open' => TRUE,
    ];
    $enabled = $config->get('release_sources') ?: ['node' => TRUE, 'tx' => TRUE, 'api' => FALSE];
    $form['sources']['release_sources'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Enabled sources'),
      '#options' => [
        'tx' => $this->t('TX files'),
        'api' => $this->t('Future API endpoint'),
        'node' => $this->t('Existing national_release nodes'),
      ],
      '#default_value' => array_keys(array_filter($enabled)),
    ];
    $form['sources']['source_priority'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Source priority'),
      '#default_value' => implode(', ', $config->get('source_priority') ?: ['tx', 'api', 'node']),
      '#description' => $this->t('Comma-separated source IDs. Earlier sources win during dedupe. Example: tx, api, node'),
    ];
    $form['sources']['timezone'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Release timezone'),
      '#default_value' => $config->get('timezone') ?: 'America/New_York',
    ];
    $form['sources']['upcoming_days'] = [
      '#type' => 'number',
      '#title' => $this->t('Upcoming days'),
      '#default_value' => $config->get('upcoming_days') ?: 7,
      '#min' => 1,
      '#max' => 365,
    ];

    $form['tx'] = [
      '#type' => 'details',
      '#title' => $this->t('TX file consumer'),
      '#open' => TRUE,
    ];
    $form['tx']['tx_input_dir'] = [
      '#type' => 'textfield',
      '#title' => $this->t('TX input directory'),
      '#default_value' => $config->get('tx_input_dir') ?: '',
      '#description' => $this->t('Absolute server path to a folder containing .tx files. Leave empty to use this module\'s data folder.'),
    ];
    $form['tx']['tx_use_module_data_fallback'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Use module data folder as fallback'),
      '#default_value' => $config->get('tx_use_module_data_fallback') !== FALSE,
    ];
    $form['tx']['tx_use_snapshot_cache'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Use TX snapshot cache'),
      '#default_value' => $config->get('tx_use_snapshot_cache') !== FALSE,
      '#description' => $this->t('Recommended. Page requests read the cached snapshot instead of reparsing files.'),
    ];
    $form['tx']['tx_rebuild_snapshot_on_cron'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Rebuild TX snapshot during cron'),
      '#default_value' => $config->get('tx_rebuild_snapshot_on_cron') !== FALSE,
    ];
    $form['tx']['active_tx_directory'] = [
      '#type' => 'item',
      '#title' => $this->t('Resolved active TX directory'),
      '#markup' => $this->t('@dir', ['@dir' => \Drupal::service('nass_release.tx_file_locator')->getActiveDirectory() ?: 'No readable directory found']),
    ];

    $form['api'] = [
      '#type' => 'details',
      '#title' => $this->t('Future API source'),
      '#open' => FALSE,
    ];
    $form['api']['api_endpoint'] = [
      '#type' => 'url',
      '#title' => $this->t('API endpoint'),
      '#default_value' => $config->get('api_endpoint') ?: '',
      '#description' => $this->t('Expected JSON format: {"releases": [{"title":"...","release_datetime":"YYYY-MM-DD HH:MM:SS"}]}'),
    ];
    $form['api']['api_timeout'] = [
      '#type' => 'number',
      '#title' => $this->t('API timeout seconds'),
      '#default_value' => $config->get('api_timeout') ?: 10,
      '#min' => 1,
      '#max' => 60,
    ];

    return parent::buildForm($form, $form_state);
  }

  public function submitForm(array &$form, FormStateInterface $form_state): void {
    parent::submitForm($form, $form_state);
    $sources = [];
    foreach (['node', 'tx', 'api'] as $source) {
      $sources[$source] = in_array($source, array_filter($form_state->getValue('release_sources') ?: []), TRUE);
    }
    $priority = array_values(array_filter(array_map('trim', explode(',', (string) $form_state->getValue('source_priority')))));

    $this->config('nass_release.adminsettings')
      ->set('nass_release_on_off_toggle', (int) $form_state->getValue('nass_release_on_off_toggle'))
      ->set('nass_release_disabled_message', $form_state->getValue('nass_release_disabled_message'))
      ->set('nass_release_host_url', rtrim((string) $form_state->getValue('nass_release_host_url'), '/') . '/')
      ->set('nass_release_textarea', $form_state->getValue('nass_release_textarea'))
      ->set('nass_release_textarea_page_top', $form_state->getValue('nass_release_textarea_page_top'))
      ->set('nass_release_textarea_page_bottom', $form_state->getValue('nass_release_textarea_page_bottom'))
      ->set('release_sources', $sources)
      ->set('source_priority', $priority ?: ['tx', 'api', 'node'])
      ->set('timezone', $form_state->getValue('timezone') ?: 'America/New_York')
      ->set('upcoming_days', (int) $form_state->getValue('upcoming_days'))
      ->set('tx_input_dir', trim((string) $form_state->getValue('tx_input_dir')))
      ->set('tx_use_module_data_fallback', (bool) $form_state->getValue('tx_use_module_data_fallback'))
      ->set('tx_use_snapshot_cache', (bool) $form_state->getValue('tx_use_snapshot_cache'))
      ->set('tx_rebuild_snapshot_on_cron', (bool) $form_state->getValue('tx_rebuild_snapshot_on_cron'))
      ->set('api_endpoint', trim((string) $form_state->getValue('api_endpoint')))
      ->set('api_timeout', (int) $form_state->getValue('api_timeout'))
      ->save();
  }

}
