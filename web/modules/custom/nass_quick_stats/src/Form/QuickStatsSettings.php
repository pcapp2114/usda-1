<?php  
/**  
 * @file  
 * Contains Drupal/nass_quick_stats\Form\QuickStatsSettings.  
 */  
namespace Drupal\nass_quick_stats\Form;  
use Drupal\Core\Form\ConfigFormBase;  
use Drupal\Core\Form\FormStateInterface;
use Drupal\file\Entity\File;

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
   * {@inheritdoc}  
   */  
  public function buildForm(array $form, FormStateInterface $form_state) {  
    $config = $this->config('nass_quick_stats.adminsettings');  
    
    $radio_value = $config->get('nass_quick_stats_api_serve');

    $form['nass_quick_stats_url'] = [  
      '#type' => 'url',  
      '#title' => $this->t('Quick Stats API base URL'),  
      '#description' => $this->t('Please enter the base URL of the Quick Stats API.'),  
      '#default_value' => $config->get('nass_quick_stats_url'),  
    ];

    $form['nass_quick_stats_key'] = [  
      '#type' => 'textfield',  
      '#title' => $this->t('Quick Stats Security API Key'),
      '#description' => $this->t('This API Key is required for the module to connect to the Quick Stats API.'),  
      '#default_value' => $config->get('nass_quick_stats_key'),  
    ];  
  
    return parent::buildForm($form, $form_state);  
  }
  
  /**  
   * {@inheritdoc}  
   */  
  public function submitForm(array &$form, FormStateInterface $form_state) {  
    parent::submitForm($form, $form_state);  
  
    $this->config('nass_quick_stats.adminsettings')  
    ->set('nass_quick_stats_url', $form_state->getValue('nass_quick_stats_url'))  
    ->save();  

    $this->config('nass_quick_stats.adminsettings')  
    ->set('nass_quick_stats_api_serve', $form_state->getValue('nass_quick_stats_api_serve'))  
    ->save();

    $this->config('nass_quick_stats.adminsettings')  
    ->set('nass_quick_stats_key', $form_state->getValue('nass_quick_stats_key'))  
    ->save();
    
  }
}