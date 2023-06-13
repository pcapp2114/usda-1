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

    $textarea = $config->get('nass_release_textarea');
    $form['nass_release_textarea'] = [  
      '#type' => 'text_format',
      '#title' => $this->t('No Release Today Message'),
      '#format' => 'full_html',
      '#description' => $this->t('Message to display if there is no release today.'),
      '#default_value' => $textarea['value'],
    ];

    return parent::buildForm($form, $form_state);  
  }
  
  /**  
   * {@inheritdoc}  
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {  
    parent::submitForm($form, $form_state);
    
    $this->config('nass_release.adminsettings')  
    ->set('nass_release_textarea', $form_state->getValue('nass_release_textarea'))  
    ->save();

  }
}