<?php

namespace Drupal\oauth_login_oauth2\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\FormBase;
use Drupal\oauth_login_oauth2\Utilities;

/**
 * Class for handling settings tab of module.
 */
class Settings extends FormBase {

  /**
   * {@inheritDoc}
   */
  public function getFormId() {
    return 'miniorange_oauth_client_settings';
  }

  /**
   * {@inheritDoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    global $base_url;
    $baseUrlValue = \Drupal::config('oauth_login_oauth2.settings')->get('miniorange_oauth_client_base_url');
    $url_path = $base_url . '/' . \Drupal::service('extension.list.module')->getPath('oauth_login_oauth2') . '/includes/Providers';

    $form['markup_library'] = [
      '#attached' => [
        'library' => [
          "oauth_login_oauth2/oauth_login_oauth2.admin",
          "oauth_login_oauth2/oauth_login_oauth2.style_settings",
          "core/drupal.dialog.ajax"
        ],
      ],
    ];

    $form['markup_top'] = [
      '#markup' => '<div class="mo_oauth_table_layout mo_oauth_container2">',
    ];

    $form['markup_custom_sign_in'] = [
      '#type' => 'fieldset',
      '#title' => t('SIGN IN SETTINGS'),
    ];

    $form['markup_custom_sign_in']['miniorange_oauth_client_base_url'] = [
      '#type' => 'textfield',
      '#title' => t('Base URL: '),
      '#default_value' => $baseUrlValue,
      '#attributes' => ['id' => 'mo_oauth_vt_baseurl', 'style' => 'width:73%;', 'placeholder' => 'Enter Base URL'],
      '#description' => '<b>Note: </b>If your provider only support HTTPS Callback URL and you have HTTP site, just save your base site URL with HTTPS here.',
      '#suffix' => '<br>',
      '#prefix' => '<hr>',
    ];

    $form['markup_custom_sign_in']['miniorange_oauth_client_siginin1'] = [
      '#type' => 'submit',
      '#button_type' => 'primary',
      '#attributes' => ['style' => 'margin: auto; display:block; '],
      '#value' => t('Update'),
    ];

    $form['markup_custom_sign_in1'] = [
      '#type' => 'fieldset',
      '#title' => t('ADVANCED SIGN IN SETTINGS <a href="licensing"><img class="mo_oauth_pro_icon1" src="' . $url_path . '/pro.png" alt="Premium and Enterprise"><span class="mo_pro_tooltip">Available in the Premium and Enterprise version</span></a><a class="mo_oauth_client_how_to_setup" href="https://developers.miniorange.com/docs/oauth-drupal/sign-in-settings#sign-in-settings-features" target="_blank">[What are Sign in settings feature]</a>'),
    ];

    $form['markup_custom_sign_in1']['miniorange_oauth_force_auth'] = [
      '#type' => 'checkbox',
      '#title' => t('Protect website against anonymous access'),
      '#disabled' => TRUE,
      '#prefix' => '<hr>',
      '#description' => t('<b>Note: </b>Users will be redirected to your OAuth server for login in case user is not logged in and tries to access website.<br><br>'),
    ];

    $form['markup_custom_sign_in1']['miniorange_oauth_auto_redirect'] = [
      '#type' => 'checkbox',
      '#title' => t('Check this option if you want to <b> Auto-redirect to OAuth Provider/Server </b>'),
      '#disabled' => TRUE,
      '#description' => t('<b>Note: </b>Users will be redirected to your OAuth server for login when the login page is accessed.<br><br>'),
    ];

    $form['markup_custom_sign_in1']['miniorange_oauth_enable_backdoor'] = [
      '#type' => 'checkbox',
      '#title' => t('Check this option if you want to enable <b>backdoor login </b>'),
      '#disabled' => TRUE,
      '#description' => t('<b>Note: </b>Checking this option creates a backdoor to login to your Website using Drupal credentials<br> incase you get locked out of your OAuth server.
                <br><b>Note down this URL: </b>Available in <a href="' . $base_url . '/admin/config/people/oauth_login_oauth2/licensing"><b>Premium, Enterprise</b></a> versions of the module.'),
    ];

    $form['markup_custom_sign_in2'] = [
      '#type' => 'fieldset',
      '#title' => t('DOMAIN & PAGE RESTRICTION <a href="licensing"><img class="mo_oauth_pro_icon1" src="' . $url_path . '/pro.png" alt="Enterprise"><span class="mo_pro_tooltip">Available in the Enterprise version</span></a><a class="mo_oauth_client_how_to_setup" href="https://developers.miniorange.com/docs/oauth-drupal/sign-in-settings#domain-restriction" target="_blank">[What is Domain and Page Restriction]</a>'),
    ];

    $form['markup_custom_sign_in2']['miniorange_oauth_client_white_list_url'] = [
      '#type' => 'textfield',
      '#title' => t('Allowed Domains'),
      '#attributes' => [ 'placeholder' => 'Enter semicolon(;) separated domains (Eg. xxxx.com; xxxx.com)'],
      '#description' => t('<b>Note: </b> Enter <b>semicolon(;) separated</b> domains to allow SSO. Other than these domains will not be allowed to do SSO.'),
      '#disabled' => TRUE,
      '#prefix' => '<hr>',
    ];

    $form['markup_custom_sign_in2']['miniorange_oauth_client_black_list_url'] = [
      '#type' => 'textfield',
      '#title' => t('Restricted Domains'),
      '#attributes' => [ 'placeholder' => 'Enter semicolon(;) separated domains (Eg. xxxx.com; xxxx.com)'],
      '#description' => t('<b>Note: </b> Enter <b>semicolon(;) separated</b> domains to restrict SSO. Other than these domains will be allowed to do SSO.'),
      '#disabled' => TRUE,
    ];

    $form['markup_custom_sign_in2']['miniorange_oauth_client_page_restrict_url'] = [
      '#type' => 'textfield',
      '#title' => t('Page Restriction'),
      '#attributes' => ['placeholder' => 'Enter semicolon(;) separated page URLs (Eg. xxxx.com/yyy; xxxx.com/yyy)'],
      '#description' => t('<b>Note: </b> Enter <b>semicolon(;) separated</b> URLs to restrict unauthorized access.'),
      '#disabled' => TRUE,
    ];

    $form['markup_custom_sign_in2']['miniorange_oauth_client_siginin'] = [
      '#type' => 'button',
      '#disabled' => TRUE,
      '#value' => t('Save Configuration'),
      '#button_type' => 'primary',
      '#attributes' => ['style' => '	margin: auto; display:block; '],
    ];

    $form['cusotm_login_logout_fieldset'] = [
      '#type' => 'fieldset',
      '#title' => 'CUSTOM LOGIN/LOGOUT <a href="licensing"><img class="mo_oauth_pro_icon1" src="' . $url_path . '/pro.png" alt="Enterprise"><span class="mo_pro_tooltip">Available in the Standard, Premium and Enterprise version</span></a>'
    ];

    $form['cusotm_login_logout_fieldset']['miniorange_oauth_client_login_url'] = [
      '#type' => 'textfield',
      '#id' => 'text_field2',
      '#required' => FALSE,
      '#disabled' => TRUE,
      '#attributes' => ['style' => 'width:79%;', 'placeholder' => 'Enter Redirect URL after Login'],
    ];

    $form['cusotm_login_logout_fieldset']['miniorange_oauth_client_logout_url'] = [
      '#type' => 'textfield',
      '#id' => 'text_field3',
      '#required' => FALSE,
      '#disabled' => TRUE,
      '#attributes' => ['style' => 'width:79%;', 'placeholder' => 'Enter Redirect URL after Logout'],
    ];

    $form['cusotm_login_logout_fieldset']['markup_role_break'] = [
      '#markup' => '<br>',
    ];

    $form['cusotm_login_logout_fieldset']['miniorange_oauth_client_attr_setup_button'] = [
      '#type' => 'submit',
      '#value' => t('Save Configuration'),
      '#disabled' => TRUE,
      '#attributes' => ['style' => '	margin: auto; display:block; '],
    ];

    $form['markup_custom_login_button'] = [
      '#type' => 'fieldset',
      '#title' => t('LOGIN BUTTON CUSTOMIZATION <a href="licensing"><img class="mo_oauth_pro_icon1" src="' . $url_path . '/pro.png" alt="Standard, Premium, Enterprise"><span class="mo_pro_tooltip">Available in the Standard, Premium and Enterprise version</span></a>'),
    ];

    $form['markup_custom_login_button']['markup_top1'] = [
      '#markup' => '<hr>',
    ];

    $form['markup_custom_login_button']['miniorange_oauth_icon_width'] = [
      '#type' => 'textfield',
      '#title' => t('Icon width'),
      '#disabled' => TRUE,
      '#description' => t('For eg.200px or 10% <br>'),
    ];

    $form['markup_custom_login_button']['miniorange_oauth_icon_height'] = [
      '#type' => 'textfield',
      '#title' => t('Icon height'),
      '#disabled' => TRUE,
      '#description' => t('For eg.60px or auto <br>'),
    ];

    $form['markup_custom_login_button']['miniorange_oauth_icon_margins'] = [
      '#type' => 'textfield',
      '#title' => t('Icon Margins'),
      '#disabled' => TRUE,
      '#description' => t('For eg. 2px 3px or auto <br>'),
    ];

    $form['markup_custom_login_button']['miniorange_oauth_custom_css'] = [
      '#type' => 'textarea',
      '#title' => t('Custom CSS'),
      '#disabled' => TRUE,
      '#attributes' => ['style' => 'width:80%', 'placeholder' => 'For eg.  .oauthloginbutton{ background: #7272dc; height:40px; padding:8px; text-align:center; color:#fff; }'],
    ];

    $form['markup_custom_login_button']['miniorange_oauth_btn_txt'] = [
      '#type' => 'textfield',
      '#title' => t('Custom Button Text'),
      '#disabled' => TRUE,
      '#attributes' => ['placeholder' => 'Login Using appname'],
    ];

    $form['markup_custom_login_button']['mo_header_style_end'] = ['#markup' => '</div>'];
    Utilities::moOAuthShowCustomerSupportIcon($form, $form_state);
    return $form;
  }

  /**
   * Submit Handler for settings tab.
   *
   * @param array $form
   *   The form elements array.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The formstate.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    global $base_url;
    $baseUrlvalue = trim($form['markup_custom_sign_in']['miniorange_oauth_client_base_url']['#value']);
    if (!empty($baseUrlvalue) && filter_var($baseUrlvalue, FILTER_VALIDATE_URL) == FALSE) {
      \Drupal::messenger()->adderror(t('Please enter a valid URL'));
      return;
    }
    \Drupal::configFactory()->getEditable('oauth_login_oauth2.settings')->set('miniorange_oauth_client_base_url', $baseUrlvalue)->save();
    $miniorange_auth_client_callback_uri = !empty($baseUrlvalue) ? $baseUrlvalue . "/mo_login" : $base_url . "/mo_login";
    \Drupal::configFactory()->getEditable('oauth_login_oauth2.settings')->set('miniorange_auth_client_callback_uri', $miniorange_auth_client_callback_uri)->save();
    \Drupal::messenger()->addMessage(t('Configurations saved successfully.'));
  }

  /**
   * Displays setup call form.
   *
   * @param array $form
   *   The form elements array.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The formstate.
   */
  public static function setup_call(array &$form, FormStateInterface $form_state) {
    Utilities::scheduleCall($form, $form_state);
  }

}
