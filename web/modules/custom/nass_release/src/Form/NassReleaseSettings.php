<?php  
/**  
 * @file  
 * Contains Drupal/nass_release\Form\NassReleaseSettings.  
 */  
namespace Drupal\nass_release\Form;  
use Drupal\Core\Form\ConfigFormBase;  
use Drupal\Core\Form\FormStateInterface;
use Drupal\file\Entity\File;

class NassReleaseSettings extends ConfigFormBase {  
  /**  
   * {@inheritdoc}  
   */  
  protected function getEditableConfigNames() {  
    return [  
      'nass_release.adminsettings',  
    ];  
  }  

  /**  
   * {@inheritdoc}  
   */  
  public function getFormId() {  
    return 'nass_release';  
  }

  /**  
   * {@inheritdoc}  
   */  
  public function buildForm(array $form, FormStateInterface $form_state) {  
    $config = $this->config('nass_release.adminsettings');

    $toggle = $config->get('nass_release_on_off_toggle');
    $form['nass_release_on_off_toggle'] = array(
      '#type' => 'radios',
      '#description' => $this->t('This will deactivate the Today\'s Releases block. The disabled message will be put in it\'s place. When this setting is changed, please clear the Drupal cache after saving the page.'),
      '#title' => $this
        ->t('Turn off Today\'s Releases'),
      '#default_value' => 1,
      '#options' => array(
        0 => $this
          ->t('Off'),
        1 => $this
          ->t('On'),
      ),
    );

    $form['nass_release_disabled_message'] = [  
      '#type' => 'textfield',  
      '#title' => $this->t('Release Disabled Message'),
      '#description' => $this->t('This is the message that will be displayed when the Today\'s Release Block is disabled.'),  
      '#default_value' => $config->get('nass_release_disabled_message'),  
    ]; 

    $form['nass_release_host_url'] = [  
      '#type' => 'url',  
      '#title' => $this->t('Today\'s Release Host URL'),  
      '#description' => $this->t('Please enter the base URL where the files loaded into the Today\'s Releases block will be pulled from The default URL is: "https://release.nass.usda.gov/reports/". Please make sure you end the URL with an ending forward slash "/".'),  
      '#default_value' => $config->get('nass_release_host_url'), 
    ];
    
    $textarea = $config->get('nass_release_textarea');
    $form['nass_release_textarea'] = [  
      '#type' => 'text_format',
      '#title' => $this->t('No Release Today Message'),
      '#format' => 'full_html',
      '#description' => $this->t('Message to display if there is no release today.'),
      '#default_value' => $textarea['value'],
    ];

    $textarea_top = $config->get('nass_release_textarea_page_top');
    if(isset($textarea_top)) {
      $textarea_top_content = $textarea_top['value'];
    } else {
      $textarea_top_content = '';
    }
    $form['nass_release_textarea_page_top'] = [  
      '#type' => 'text_format',
      '#title' => $this->t('Todays Release Page Top Content'),
      '#format' => 'full_html',
      '#description' => $this->t('Todays Release Page Top Content.'),
      '#default_value' => $textarea_top_content,
    ];

    $textarea_bottom = $config->get('nass_release_textarea_page_bottom');
    if(isset($textarea_bottom)) {
      $textarea_bottom_content = $textarea_bottom['value'];
    } else {
      $textarea_bottom_content = '';
    }
    $form['nass_release_textarea_page_bottom'] = [  
      '#type' => 'text_format',
      '#title' => $this->t('Todays Release Page Bottom Content'),
      '#format' => 'full_html',
      '#description' => $this->t('Todays Release Page Bottom Content.'),
      '#default_value' => $textarea_bottom_content,
    ];   

    return parent::buildForm($form, $form_state);  
  }
  
  /**  
   * {@inheritdoc}  
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {  
    parent::submitForm($form, $form_state);
    
    $this->config('nass_release.adminsettings')  
    ->set('nass_release_on_off_toggle', $form_state->getValue('nass_release_on_off_toggle'))  
    ->save();

    $this->config('nass_release.adminsettings')  
    ->set('nass_release_host_url', $form_state->getValue('nass_release_host_url'))  
    ->save();

    $this->config('nass_release.adminsettings')  
    ->set('nass_release_textarea', $form_state->getValue('nass_release_textarea'))  
    ->save();

    $this->config('nass_release.adminsettings')  
    ->set('nass_release_textarea_page_top', $form_state->getValue('nass_release_textarea_page_top'))  
    ->save();

    $this->config('nass_release.adminsettings')  
    ->set('nass_release_textarea_page_bottom', $form_state->getValue('nass_release_textarea_page_bottom'))  
    ->save();    

    $this->config('nass_release.adminsettings')  
    ->set('nass_release_disabled_message', $form_state->getValue('nass_release_disabled_message'))  
    ->save();  

  }
}