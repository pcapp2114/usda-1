<?php

namespace Drupal\oauth_login_oauth2\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\ReplaceCommand;
use Drupal\Core\Ajax\RedirectCommand;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\user\Entity\User;
use Drupal\oauth_login_oauth2\MiniorangeOAuthClientSupport;

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

    $form['mo_oauth_trial_email_address'] = [
      '#type' => 'email',
      '#title' => $this->t('Email'),
      '#default_value' => self::getEmail(),
    ];

    // Description textarea.
    $form['mo_oauth_trial_description'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Use Case Description'),
      '#attributes' => ['placeholder' => t('Describe your use case here!'), 'style' => 'width:99%;'],
    ];

    $form['submit_button_other_options'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
      '#attributes' => [
        'class' => ['other-options-submit', 'use-ajax', 'button--primary'],
      ],
      '#prefix' => '<div class="other-options-submit-wrapper">',
      '#suffix' => '</div>',
      '#ajax' => [
        'callback' => [$this, 'submitModalFormAjax'],
        'event' => 'click',
      ],
    ];

    $form['#attached']['library'][] = 'core/drupal.dialog.ajax';
    return $form;
  }

  /**
   * Ajax callback to update the form elements.
   */
  public function updateFormElements(array &$form, FormStateInterface $form_state) {
    return $form['additional_fields_wrapper'];
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
    $email = $form['mo_oauth_trial_email_address']['#value'];
    $use_case = $form['mo_oauth_trial_description']['#value'];
    // If there are any form errors, AJAX replace the form.
    if($form_state->hasAnyErrors()){
      $response->addCommand(new ReplaceCommand('#modal_example_form', $form));
    }elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      \Drupal::messenger()->addMessage(t('The email address <b><i>' . $email . '</i></b> is not valid.'), 'error');
      $response->addCommand(new ReplaceCommand('#modal_example_form', $form));
    }elseif(empty(trim($use_case))){
      \Drupal::messenger()->addMessage(t('The Use Case Description is required.'), 'error');
      $response->addCommand(new ReplaceCommand('#modal_example_form', $form));
    }
    else {
      $query_type = 'Trial Request';
      $query = "</b><br><br> <b>Usecase : </b>".$use_case.'</code><pre>';
      $support = new MiniorangeOAuthClientSupport($email, '', $query, $query_type);
      $support_response = json_decode($support->sendSupportQuery(), TRUE);
      if (isset($support_response['status']) && $support_response['status'] == "SUCCESS") {
        \Drupal::messenger()->addStatus(t('Success! Trial query successfully sent. We will send you an email including the steps to activate the trial shortly. Please check your inbox for further instructions.'));
      }else {
        \Drupal::messenger()->addStatus(t('Error sending Trial request. Please reach out to <a href="mailto:drupalsupport@xecurify.com">drupalsupport@xecurify.com</a>'));
      }
      $response->addCommand(new RedirectCommand(Url::fromRoute('oauth_login_oauth2.config_clc')->toString()));
    }
    return $response;
  }

  /**
   * {@inheritDoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) { }

  public static function getEmail(){
    return User::load(\Drupal::currentUser()->id())->getEmail();
  }

}
