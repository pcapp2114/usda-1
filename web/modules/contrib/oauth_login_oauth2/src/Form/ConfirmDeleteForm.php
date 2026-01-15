<?php

namespace Drupal\oauth_login_oauth2\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Url;
use Drupal\oauth_login_oauth2\Utilities;

/**
 * Class for confirmation before deleting configurations.
 */
class ConfirmDeleteForm extends FormBase {

  /**
   * {@inheritDoc}
   */
  public function getFormId() {
    return 'miniorange_oauth_client_confirm_delete';
  }

  /**
   * {@inheritDoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['markup_library'] = [
      '#attached' => [
        'library' => [
          "oauth_login_oauth2/oauth_login_oauth2.admin",
          "oauth_login_oauth2/oauth_login_oauth2.style_settings",
          'core/drupal.dialog.ajax',
        ],
      ],
    ];
      
    $config = \Drupal::config('oauth_login_oauth2.settings');
    $client_app = $config->get('miniorange_oauth_login_config_application');
    $action = \Drupal::request()->query->get('action');
    
    $form['confirm_fieldset'] = [
     '#type' => 'fieldset'
    ];
   
    $message = $this->t('Are you sure you want to @action the configuration for @app?', [ '@action' => $action, '@app' => $client_app,  ]);

    $form['confirm_fieldset']['message'] = [
      '#markup' => $message,
      '#suffix' => '<br>',
    ];

    $form['confirm_fieldset'][$action] = [
      '#type' => 'submit',
      '#value' => $action === 'delete' ? $this->t('Delete') : $this->t('Reset'),
      '#submit' => ['::resetMoConfig'],
      '#attributes' => ['class' => ['button', 'button--primary']],
    ];

    $form['confirm_fieldset']['cancel'] = [
      '#type' => 'link',
      '#title' => $this->t('Cancel'),
      '#url' => Url::fromRoute('oauth_login_oauth2.config_clc')->setOption('query', $action === 'delete' ? ['app_name' => $client_app] : ['action' => 'update', 'app' => $client_app]),
      '#attributes' => [
        'class' => ['button', 'cancel-button'],
      ],
    ];
    

    Utilities::moOAuthShowCustomerSupportIcon($form, $form_state);
    return $form;
  }

  /**
   * {@inheritDoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {}

  public static function resetMoConfig(array &$form, FormStateInterface $form_state) {
    $config =  \Drupal::configFactory()->getEditable('oauth_login_oauth2.settings');
    $all_keys = $config->getRawData();

    foreach($all_keys as $key=> $value){
      if ($key !== 'miniorange_oauth_login_config_status') {
        $config->clear($key);
      }
    }
    $config->set('miniorange_oauth_login_config_status', 'select_application')->save();
    \Drupal::messenger()->addMessage(t('Application deleted successfully.'));
    $form_state->setRedirect('oauth_login_oauth2.config_clc');
  }
}
