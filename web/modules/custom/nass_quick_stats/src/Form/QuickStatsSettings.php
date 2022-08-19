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
    $form['nass_quick_stats_api_serve'] = array(
      '#type' => 'radios',
      '#title' => $this->t('Where is Quick Stats data being served from?'),
      '#options' => array(
        'JSON_ENDPOINT' => $this->t('Use NASS Provided Endpoint.'),
        'JSON' => $this->t('Use JSON file. This file must be in the correct format and named "quick_stats.json".'),        
      ),
      '#required' => TRUE,
      '#default_value' => $radio_value ?? 'JSON_ENDPOINT',    
    );

    $form['nass_quick_stats_url'] = [  
      '#type' => 'url',  
      '#title' => $this->t('Quick Stats API base URL'),  
      '#description' => $this->t('Please enter the base URL of the Quick Stats API.'),  
      '#default_value' => $config->get('nass_quick_stats_url'),  
    ];

    $form['nass_quick_stats_hash'] = [  
      '#type' => 'textfield',  
      '#title' => $this->t('Quick Stats Security API Key'),  
      '#description' => $this->t('This API Key is required for the module to connect to the Quick Stats API.'),  
      '#default_value' => $config->get('nass_quick_stats_hash'),  
    ];  

    $form['nass_quick_stats_markup_top'] = [
      '#markup' => '<hr>',
      '#allowed_tags' => ['hr','br',], 
    ];

    $form['nass_quick_stats_file_upload'] = [
      	'#type' => 'managed_file',
        '#description' => $this->t('Use this field to upload a json file to use for the Quick Stats API. The file must be named "quick_stats.json".'),      	
      	'#title' => $this->t('Quick Stats JSON File'),
      	'#upload_location' => 'public://json',
      	'#upload_validators' => [
      	  'file_validate_extensions' => ['json'],
      	],       	
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
    ->set('nass_quick_stats_hash', $form_state->getValue('nass_quick_stats_hash'))  
    ->save();
    
    $field_file = $form_state->getValue('nass_quick_stats_file_upload');
    $file = File::load($field_file[0]);
    if (!empty($file)) {
      $file_uri = $file->getFileUri();
      
      //Setting the name in database
      $file->setFilename('quick_stats.json');
      $file->setFileUri('public://json/quick_stats.json');
      $file->setPermanent();
      $file->save();

      rename($file_uri, 'public://json/quick_stats.json');
    }
  }
}