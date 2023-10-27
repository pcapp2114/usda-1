<?php
/**
 * @file
 * Contains Drupal/nass_eauth_login\Form\EAuthLoginForm.
 */
namespace Drupal\nass_quick_stats\Form;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\file\Entity\File;

class EAuthLoginForm extends ConfigFormBase {
  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'nass_eauth_login.adminsettings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'nass_eauth_login_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('nass_eauth_login.adminsettings');

//    $form['nass_quick_stats_url'] = [
//      '#type' => 'url',
//      '#title' => $this->t('Quick Stats API base URL'),
//      '#description' => $this->t('Please enter the base URL of the Quick Stats API.'),
//      '#default_value' => $config->get('nass_quick_stats_url'),
//    ];

//    $form['nass_quick_stats_key'] = [
//      '#type' => 'textfield',
//      '#title' => $this->t('Quick Stats Security API Key'),
//      '#description' => $this->t('This API Key is required for the module to connect to the Quick Stats API.'),
//      '#default_value' => $config->get('nass_quick_stats_key'),
//    ];
//
//    $checkbox = $config->get('nass_quick_stats_disable');
//    $form['nass_quick_stats_disable'] = array(
//      '#type' => 'checkbox',
//      '#title' => $this->t('Disable QuickStats'),
//      '#description' => $this->t('Disable Quick Stats from rendering. The textarea below will continue to display.'),
//      '#default_value' => $checkbox,
//    );
//
//    $form['nass_quick_stats_disabled_msg'] = [
//      '#type' => 'textfield',
//      '#title' => $this->t('Optional Quick Stats Disabled Message'),
//      '#description' => $this->t('You use to give a helpful obvious message that Quick Stats is disabled. This will only display if the QuickStats Disabled toggle is in the "on" position.'),
//      '#default_value' => $config->get('nass_quick_stats_disabled_msg'),
//    ];
//
//    $textarea = $config->get('nass_quick_stats_textarea');
//    $form['nass_quick_stats_textarea'] = [
//      '#type' => 'text_format',
//      '#title' => $this->t('Message'),
//      '#format' => 'full_html',
//      '#description' => $this->t('Message display to customer contacts.'),
//      '#default_value' => $textarea['value'],
//    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

//    $this->config('nass_quick_stats.adminsettings')
//    ->set('nass_quick_stats_url', $form_state->getValue('nass_quick_stats_url'))
//    ->save();
//
//    $this->config('nass_quick_stats.adminsettings')
//    ->set('nass_quick_stats_disable', $form_state->getValue('nass_quick_stats_disable'))
//    ->save();
//
//    $this->config('nass_quick_stats.adminsettings')
//    ->set('nass_quick_stats_disabled_msg', $form_state->getValue('nass_quick_stats_disabled_msg'))
//    ->save();
//
//    $this->config('nass_quick_stats.adminsettings')
//    ->set('nass_quick_stats_key', $form_state->getValue('nass_quick_stats_key'))
//    ->save();
//
//    $this->config('nass_quick_stats.adminsettings')
//    ->set('nass_quick_stats_textarea', $form_state->getValue('nass_quick_stats_textarea'))
//    ->save();
  }
}
