<?php

namespace Drupal\oauth_login_oauth2\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\oauth_login_oauth2\Utilities;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Url;
use Drupal\Core\Render\Markup;

/**
 * Class for handling Mapping tab.
 */
class MiniorangeMapping extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'miniorange_mapping';
  }

  /**
   * {@inheritDoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $base_url = \Drupal::request()->getSchemeAndHttpHost().\Drupal::request()->getBasePath();
    $url_path = $base_url . '/' . \Drupal::service('extension.list.module')->getPath('oauth_login_oauth2') . '/includes/Providers';
    $form['markup_library'] = [
      '#attached' => [
        'library' => [
          "oauth_login_oauth2/oauth_login_oauth2.admin",
          "oauth_login_oauth2/oauth_login_oauth2.style_settings",
          "oauth_login_oauth2/oauth_login_oauth2.email_username_attribute",
          "core/drupal.dialog.ajax"
        ],
      ],
    ];

    $form['markup_top'] = [
      '#markup' => '<div class="mo_oauth_table_layout mo_oauth_container2">',
    ];

    $form['mo_attr_outline'] = [
      '#type' => 'fieldset',
    ];

    $form['mo_attr_outline']['mo_attribute_mapping'] = [
      '#markup' => '<div id="attribute_mapping"><h2>'.t('Attribute Mapping').'</h2></div><hr>',
    ];

    $form['mo_attr_outline']['mo_vt_id_start1'] = [
        '#markup' => '<div class="mo_oauth_client_highlight_background_note_1">'.t('Attributes are the user details that are stored by your OAuth server(s). Attribute Mapping helps you get user attributes/fields from your OAuth server and map them to your Drupal site user attributes.').'</div>
                          <br><div id = "mo_oauth_vt_attrn" class="container-inline" style="padding-bottom: 5px;"> <b>'.t('Note: ').'</b>'.t('Please select the attribute name with ').'<b>'.t('email').'</b>'.t(' from the dropdown of ').'<b>'.t('OAuth Server Attributes ').'</b>'.t('for successful SSO.').'</div>',
    ];
    $config = \Drupal::config('oauth_login_oauth2.settings');
    $attrs = $config->get('miniorange_oauth_client_attr_list_from_server');
    $attrs = isset($attrs) && !empty($attrs) ? json_decode($attrs, TRUE) : '';
    $options = [
      'select' => 'Select an Email Attribute',
    ];
    if (is_array($attrs)) {
      foreach ($attrs as $key => $value) {
        if (is_array($value)) {
          foreach ($value as $key1 => $value1) {
            $options[$key1] = $key1;
          }
          continue;
        }
        $options[$key] = $key;
      }
    }

    $data = ['email_attr' => 'miniorange_oauth_client_email_attr_val'];

    $form['mo_attr_outline']['miniorange_oauth_login_mapping'] = [
      '#type' => 'table',
      '#responsive' => TRUE,
      '#header' => [
        t('Drupal Attributes'),
        t('OAuth Server Attributes'),
      ],
      '#attributes' => ['style' => 'border-collapse: separate;'],
    ];

    foreach ($data as $key => $value) {
      $row = self::miniorangeOauthClientTableDataMappinga($key, $value, $options, $config);
      $form['mo_attr_outline']['miniorange_oauth_login_mapping'][$key] = $row;
    }

    $form['mo_attr_outline']['miniorange_oauth_client_attr_setup_button_2'] = [
      '#type' => 'submit',
      '#value' => t('Save Attribute Mapping'),
      '#submit' => ['::miniorangeOauthClientAttrSetupSubmit'],
      '#button_type' => 'primary',
      '#prefix' => '<br>',
      '#attributes' => ['style' => 'margin: auto; display:block; '],
    ];

    $form['markup_top_vt_start2'] = [
      '#type' => 'details',
      '#title' => t('BACKUP/IMPORT CONFIGURATIONS'),
    ];

    $form['markup_top_vt_start2']['markup_1'] = [
      '#markup' => '<br><div class="mo_oauth_client_highlight_background_note_1"><p><b>'.t('NOTE: ').'</b>'.t('This tab will help you to transfer your module configurations when you change your Drupal instance.').'
                      <br>'.t('Example: When you switch from test environment to production.').'<br>'.t('Follow these 3 simple steps to do that:').'<br>
                      <br><strong>1.</strong> '.t('Download module configuration file by clicking on the Download Configuration button given below.').'
                      <br><strong>2.</strong> '.t('Install the module on new Drupal instance.').'<br><strong>3.</strong> '.t('Upload the configuration file in Import module Configurations section.').'<br>
                      <br><b>'.t('And just like that, all your module configurations will be transferred!').'</b></p></div><br><div id="Exort_Configuration"><h3>'.t('Backup/ Export Configuration ').'&nbsp;&nbsp;</h3><hr/><p>'.
                                t('Click on the button below to download module configuration.').'</p>',
    ];

    $form['markup_top_vt_start2']['miniorange_oauth_imo_option_exists_export'] = [
      '#type' => 'submit',
      '#value' => t('Download Module Configuration'),
      '#limit_validation_errors' => [],
      '#submit' => ['::miniorange_import_export'],
      '#suffix' => '<br/><br/></div>',
    ];

    $form['markup_top_vt_start2']['markup_prem_plan'] = [
      '#markup' => '<div id="Import_Configuration"><br/><h3>'.t('Import Configuration').'</h3><hr><br>
                      <div class="mo_oauth_highlight_background_note_1"><b>'.t('Note: ').'</b>'.t('Available in').'
                               <a href="' . Url::fromRoute('oauth_login_oauth2.licensing')->toString().'"><strong>'.t('Standard, Premium and Enterprise').'</strong></a> '.t('versions of the module').'</div>',
    ];

    $form['markup_top_vt_start2']['markup_import_note'] = [
      '#markup' => '<p>'.t('This tab will help you to').'<span style="font-weight: bold"> '.t('Import your module configurations').'</span> '.t('when you change your Drupal instance.').'</p>
                       <p>'.t('choose ').'<b>"json"</b> '.t('Extened module configuration file and upload by clicking on the button given below. ').'</p>',
    ];

    $form['markup_top_vt_start2']['import_Config_file'] = [
      '#type' => 'file',
      '#disabled' => TRUE,
    ];

    $form['markup_top_vt_start2']['miniorange_oauth_import'] = [
      '#type' => 'submit',
      '#value' => t('Upload'),
      '#disabled' => TRUE,
      '#suffix' => '<br><br></div>',
    ];

    $form['markup_custom_attribute'] = [
      '#type' => 'fieldset',
      '#title' => Markup::create($this->t('CUSTOM ATTRIBUTE MAPPING') .' <a href="licensing"><img class="mo_oauth_pro_icon1" src="' . $url_path . '/pro.png" alt="Premium and Enterprise"><span class="mo_pro_tooltip">'.$this->t('Available in the Standard, Premium and Enterprise version').'</span></a><a class="mo_oauth_client_how_to_setup" href="https://www.drupal.org/docs/contributed-modules/drupal-oauth-openid-connect-login-oauth2-client-sso-login/oauth-feature-handbook/user-entity-fields-mapping-oauth-oidc-login" target="_blank">'.$this->t('[What is Attribute Mapping and How to Set up]').'</a>'),
    ];

    $form['markup_custom_attribute']['attribute_mapping_info'] = [
      '#markup' => '<hr><div class="mo_oauth_client_highlight_background_note_1">'.t('This feature allows you to map the user attributes from your OAuth server to the user attributes in Drupal.').'</div>',
    ];

    $form['markup_custom_attribute']['miniorange_oauth_attr_name'] = [
      '#type' => 'textfield',
      '#prefix' => '<div><table><tr><td>',
      '#suffix' => '</td>',
      '#id' => 'text_field',
      '#title' => t('OAuth Server Attribute Name'),
      '#attributes' => ['style' => 'width:73%;background-color: hsla(0,0%,0%,0.08) !important;', 'placeholder' => t('Enter Server Attribute Name')],
      '#required' => FALSE,
      '#disabled' => TRUE,
    ];
    $form['markup_custom_attribute']['miniorange_oauth_server_name'] = [
      '#type' => 'textfield',
      '#id' => 'text_field1',
      '#prefix' => '<td>',
      '#suffix' => '</td>',
      '#title' => t('Drupal Machine Name'),
      '#attributes' => ['style' => 'width:73%;background-color: hsla(0,0%,0%,0.08) !important;', 'placeholder' => t('Enter Drupal Machine Name')],
      '#required' => FALSE,
      '#disabled' => TRUE,
    ];
    $form['markup_custom_attribute']['miniorange_oauth_add_name'] = [
      '#prefix' => '<td>',
      '#suffix' => '</td>',
      '#type' => 'button',
      '#disabled' => 'true',
      '#value' => '+',
    ];
    $form['markup_custom_attribute']['miniorange_oauth_sub_name'] = [
      '#prefix' => '<td>',
      '#suffix' => '</td></tr></table></div>',
      '#type' => 'button',
      '#disabled' => 'true',
      '#value' => '-',
    ];

    $form['markup_custom_role_mapping'] = [
      '#type' => 'fieldset',
      '#title' => Markup::create($this->t('CUSTOM ROLE MAPPING').' <a href="licensing"><img class="mo_oauth_pro_icon1" src="' . $url_path . '/pro.png" alt="Premium and Enterprise"><span class="mo_pro_tooltip">'.$this->t('Available in the Premium and Enterprise version').'</span></a><a class="mo_oauth_client_how_to_setup" href="https://www.drupal.org/docs/contributed-modules/drupal-oauth-openid-connect-login-oauth2-client-sso-login/oauth-feature-handbook/user-role-mapping-oauth-oidc-login" target="_blank">'.$this->t('[What is Role Mapping and How to Set up]').'</a>'),
    ];

    $form['markup_custom_role_mapping']['role_mapping_info'] = [
      '#markup' => '<hr><div class="mo_oauth_client_highlight_background_note_1">'.t('This feature allows you to map OAuth Server roles/groups to below configured Drupal Role.').'</div>',
    ];

    $form['markup_custom_role_mapping']['miniorange_disable_attribute'] = [
      '#type' => 'checkbox',
      '#title' => t('Do not update existing user&#39;s role.'),
      '#disabled' => TRUE,
      '#prefix' => '<br>',
    ];
    $form['markup_custom_role_mapping']['miniorange_oauth_disable_role_update'] = [
      '#type' => 'checkbox',
      '#title' => t('Check this option if you do not want to update user role if roles not mapped. '),
      '#disabled' => TRUE,
    ];

    $mrole = Utilities::getUserRoles(false, true);

    $form['markup_custom_role_mapping']['miniorange_oauth_default_mapping'] = [
      '#type' => 'select',
      '#id' => 'miniorange_oauth_client_app',
      '#title' => t('Select default group for the new users'),
      '#options' => $mrole,
      '#attributes' => ['style' => 'width:73%;'],
      '#disabled' => TRUE,
    ];

    foreach ($mrole as $roles) {
      $rolelabel = str_replace(' ', '', $roles);
      $form['markup_custom_role_mapping']['miniorange_oauth_role_' . $rolelabel] = [
        '#type' => 'textfield',
        '#title' => t($roles),
        '#attributes' => ['style' => 'width:73%;background-color: hsla(0,0%,0%,0.08) !important;', 'placeholder' => t('Semi-colon(;) separated Group/Role value for ') . $roles],
        '#disabled' => TRUE,
      ];
    }

    $form['mo_header_style_end'] = ['#markup' => '</div>'];

    Utilities::moOAuthShowCustomerSupportIcon($form, $form_state);
    return $form;
  }

  /**
   * {@inheritDoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
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

  /**
   * Clears attrs list received from OAuth Server.
   *
   * @param array $form
   *   The form elements array.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The formstate.
   */
  public function clearAttrList(&$form, $form_state) {
    \Drupal::configFactory()->getEditable('oauth_login_oauth2.settings')->clear('miniorange_oauth_client_attr_list_from_server')->save();
    Utilities::showAttrListFromIdp($form, $form_state);
  }

  /**
   * Generates form elements for mapping section.
   *
   * @param string $key
   *   The key of form element.
   * @param string $value
   *   The config variable name.
   * @param array $options
   *   attrs array.
   * @param object $config
   *   The config property.
   *
   * @return array
   *   Returns array of form elements.
   */
  public function miniorangeOauthClientTableDataMappinga($key, $value, $options, $config) {
    if ($key == 'email_attr') {
      $row[$key] = [
        '#markup' => '<div class="mo-mapping-floating"><strong>Email: </strong></div>',
      ];

      $row['miniorange_oauth_client_email_select'] = [
        '#type' => 'select',
        '#id' => 'miniorange_oauth_client_email_select',
        '#default_value' => $config->get($value),
        '#options' => $options,
      ];
    }
    return $row;
  }


  /**
   * Exports module configurations.
   */
  public function miniorange_import_export() {
    $tab_class_name = [
      'OAuth Login Configuration' => 'mo_options_enum_client_configuration',
      'Attribute Mapping' => 'mo_options_enum_attribute_mapping',
      'Sign In Settings' => 'mo_options_enum_signin_settings',
    ];

    $configuration_array = [];
    foreach ($tab_class_name as $key => $value) {
      $configuration_array[$key] = self::mo_get_configuration_array($value);
    }

    $configuration_array["Version_dependencies"] = self::mo_get_version_informations();
    header("Content-Disposition: attachment; filename = miniorange_oauth_client_config.json");
    echo(json_encode($configuration_array, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    exit;
  }

  /**
   * Submit Handler for mapping.
   *
   * @param array $form
   *   The form elements array.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The formstate.
   */
  public function miniorangeOauthClientAttrSetupSubmit(array &$form, FormStateInterface $form_state) {
    $configFactory = \Drupal::configFactory()->getEditable('oauth_login_oauth2.settings');
    $form_values = $form_state->getValues();
    $attrs = $configFactory->get('miniorange_oauth_client_attr_list_from_server');
    $app_link = $configFactory->get('miniorange_auth_client_display_link');
    if(!empty($attrs)){
      $email_attr = trim($form_values['miniorange_oauth_login_mapping']['email_attr']['miniorange_oauth_client_email_select']);
      $configFactory->set('miniorange_oauth_client_email_attr_val', $email_attr)->save();
      \Drupal::messenger()->addMessage(t('Attribute Mapping saved successfully. Please go to your Drupal site’s login page where you will find the <b>@link</b> link.', ['@link' => $app_link,]));
    }else{
      \Drupal::messenger()->addError(t("Please ensure to test the configuration by clicking the <b>Perform Test Configuration</b> button. Once tested, proceed to map the email attribute."));
    }
    return;
  }

  /**
   * Creates array of fields with its config varaible value of specific tab.
   *
   * @param string $class_name
   *   The name of tab.
   */
  public function mo_get_configuration_array($class_name) {
    $class_object = Utilities::getVariableArray($class_name);
    $mo_array = [];
    foreach ($class_object as $key => $value) {
      $mo_option_exists = \Drupal::config('oauth_login_oauth2.settings')->get($value);
      if ($mo_option_exists) {
        $mo_array[$key] = $mo_option_exists;
      }
    }
    return $mo_array;
  }

  /**
   * Creates array php extension and module versions.
   *
   * @return array
   *   Return array version info.
   */
  public function mo_get_version_informations() {
    $array_version = [];
    $array_version["PHP_version"] = phpversion();
    $array_version["Drupal_version"] = \DRUPAL::VERSION;
    $array_version["OPEN_SSL"] = self::mo_oauth_is_openssl_installed();
    $array_version["CURL"] = self::mo_oauth_is_curl_installed();
    $array_version["ICONV"] = self::mo_oauth_is_iconv_installed();
    $array_version["DOM"] = self::mo_oauth_is_dom_installed();
    return $array_version;
  }

  /**
   * Checks if opessl is installed or not.
   *
   * @return int
   *   Return 1 if installed else 0.
   */
  public function mo_oauth_is_openssl_installed() {
    return (in_array('openssl', get_loaded_extensions()) ? 1 : 0);
  }

  /**
   * Checks if cURL is installed or not.
   *
   * @return int
   *   Return 1 if installed else 0.
   */
  public function mo_oauth_is_curl_installed() {
    return (in_array('curl', get_loaded_extensions()) ? 1 : 0);
  }

  /**
   * Checks if iconv is installed or not.
   *
   * @return int
   *   Return 1 if installed else 0.
   */
  public function mo_oauth_is_iconv_installed() {
    return (in_array('iconv', get_loaded_extensions()) ? 1 : 0);
  }

  /**
   * Checks if dom is installed or not.
   *
   * @return int
   *   Return 1 if installed else 0.
   */
  public function mo_oauth_is_dom_installed() {
    return (in_array('dom', get_loaded_extensions()) ? 1 : 0);
  }

}
