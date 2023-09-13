<?php

namespace Drupal\oauth_login_oauth2\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\ReplaceCommand;
use Drupal\Core\Ajax\RedirectCommand;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\oauth_login_oauth2\MiniorangeOAuthClientConstants;
use Drupal\oauth_login_oauth2\MiniorangeOAuthClientSupport;
use Drupal\oauth_login_oauth2\Utilities;

/**
 * Class for handling request trial form.
 */
class MoOAuthRequestDemo extends FormBase {

  /**
   * {@inheritDoc}
   */
  public function getFormId() {
    return 'oauth_login_oauth2_request_demo';
  }

  /**
   * {@inheritDoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $options = NULL) {

    $form['#prefix'] = '<div id="modal_example_form">';
    $form['#suffix'] = '</div>';
    $form['status_messages'] = [
      '#type' => 'status_messages',
      '#weight' => -10,
    ];

    $user_email = \Drupal::config('oauth_login_oauth2.settings')->get('miniorange_oauth_client_customer_admin_email');

    $form['mo_oauth_trial_email_address'] = [
      '#type' => 'email',
      '#title' => t('Email'),
      '#default_value' => $user_email,
      '#required' => TRUE,
      '#attributes' => ['placeholder' => t('Enter your email'), 'style' => 'width:99%;margin-bottom:1%;'],
    ];

    $form['featurlist_table_title'] = [
      '#markup' => '<b>Select features you are most interested in</b>'
    ];
  
    $form['select_all_feature'] = [
      '#type' => 'checkbox',
      '#title' => t('Select All Features'),
      '#title' => t('Select All Features'),
      '#attributes' => [
        'name' => 'select-all-features',
      ],
    ];
  
    $form['mo_featurelist_table'] = [
      '#type' => 'table',
    ];
    
    $feature_list = Utilities::getOAuthFeaturelist();
    $features = array_chunk($feature_list, 3);
    $counter = 0;
    foreach ($features as $chunk) {
      $row = [];
      foreach ($chunk as $key => $value) {
        $row[$value] = [
          '#type' => 'checkbox',
          '#title' => $value,
          '#default_value' => 0,
          '#states' => [
            'checked' => [
                ':input[name="select-all-features"]' => ['checked' => TRUE],
            ],
          ],
        ];
      }
      $form['mo_featurelist_table'][$counter++] = $row;
    }

    $form['mo_oauth_trial_description'] = [
      '#type' => 'textarea',
      '#rows' => 4,
      '#required' => TRUE,
      '#title' => t('Description'),
      '#attributes' => ['placeholder' => t('Describe your use case here!'), 'style' => 'width:99%;'],
    ];

    $form['mo_oauth_trial_no_feature_listed'] = [
      '#markup' => '<p>if you are not sure which features are suitable for you, please contact us at <a href="mailto:drupalsupport@xecurify.com">drupalsupport@xecurify.com</a> and we will assist you.</p>'
    ];

    $form['actions'] = ['#type' => 'actions'];
    $form['actions']['send'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
      '#attributes' => [
        'class' => [
          'use-ajax',
          'button--primary',
        ],
      ],
      '#ajax' => [
        'callback' => [$this, 'submitModalFormAjax'],
        'event' => 'click',
      ],
    ];

    $form['#attached']['library'][] = 'core/drupal.dialog.ajax';
    return $form;
  }

  /**
   * Submit handler for trial request query.
   *
   * @param array $form
   *   The form elements array.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The formstate.
   *
   * @return Drupal\Core\Ajax\AjaxResponse
   *   Returns ajaxresponse object.
   */
  public function submitModalFormAjax(array $form, FormStateInterface $form_state) {
    $response = new AjaxResponse();
    // If there are any form errors, AJAX replace the form.
    if ($form_state->hasAnyErrors()) {
      $response->addCommand(new ReplaceCommand('#modal_example_form', $form));
    }
    else {
      $email = $form['mo_oauth_trial_email_address']['#value'];
      $query_type = 'Trial Request';

      $interested_features = $form_state->getValues()['mo_featurelist_table'];
      $features = [];
      foreach($interested_features as $list){
        foreach($list as $key => $value){
          if($value == '1'){
            array_push($features, $key);
          }  
        }
      }
      
      $selected_features = "<br> <pre style=\"border:1px solid #444;padding:10px;\"><code><br> <b>Interested Features : </b>". implode(', ' , $features);
      $query = "$selected_features </b><br><br> <b>Usecase : </b>". $form['mo_oauth_trial_description']['#value'].'</code><pre>';

      $support = new MiniorangeOAuthClientSupport($email, '', $query, $query_type);
      $support_response = json_decode($support->sendSupportQuery(), TRUE);

      if (isset($support_response['status']) && $support_response['status'] == "SUCCESS") {
        \Drupal::messenger()->addStatus(t('Success! Trial query successfully sent. We will send you an email including the steps to activate the trial shortly. Please check your inbox for further instructions.'));
      }
      else {
        \Drupal::messenger()->addStatus(t('Error sending Trial request. Please reach out to <a href="mailto:drupalsupport@xecurify.com">drupalsupport@xecurify.com</a>'));
      }
      $response->addCommand(new RedirectCommand(Url::fromRoute('oauth_login_oauth2.config_clc')->toString()));
    }
    return $response;
  }

  /**
   * {@inheritDoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $interested_features = $form_state->getValues()['mo_featurelist_table'];
    $flag = false;
    foreach($interested_features as $list){
      if(in_array(1, $list))
       $flag = true;
    }
    
    if(!$flag){
      $form_state->setErrorByName('mo_featurelist_table', t('Features field required. Please select features you are most interested in'));
    }
  }

  /**
   * {@inheritDoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {}

}
