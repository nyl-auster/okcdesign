<?php

function okcdesign_form_system_theme_settings_alter(&$form, $form_state) {

  include 'inc/okcdesign_plugins_manager.php';

  $form['okcdesign'] = array(
    '#type' => 'vertical_tabs',
    '#weight' => -10,
  );

  $jquery_version = variable_get('jquery_update_jquery_version', '1.5');

  if (!module_exists('jquery_update') || !version_compare($jquery_version, '1.10', '>=')) {
    drupal_set_message(t('!module was not found, or your version of jQuery does not meet the minimum version requirement. Zurb Foundation requires jQuery 1.7 or higher. Please install !module, or Zurb Foundation plugins may not work correctly.',
      array(
        '!module' => l('jQuery Update', 'https://drupal.org/project/jquery_update', array('external' => TRUE, 'attributes' => array('target' => '_blank'))),
      )
    ), 'warning', FALSE);
  }

  /*
   * General Settings.
   */
  $form['okcdesign']['general'] = array(
    '#type' => 'fieldset',
    '#title' => t('General Settings'),
  );

  $form['okcdesign']['general']['theme_settings'] = $form['theme_settings'];
  unset($form['theme_settings']);

  $form['okcdesign']['general']['logo'] = $form['logo'];
  unset($form['logo']);

  $form['okcdesign']['general']['favicon'] = $form['favicon'];
  unset($form['favicon']);


  $form['okcdesign']['plugins'] = array(
    '#type' => 'fieldset',
    '#title' => t('Plugins'),
    '#description' => t('Apply foundation styles to Drupal elements'),
  );

  $options = array();
  foreach(okcdesign_get_plugins() as $id => $datas) {
    $description = isset($datas['description']) ? $datas['description'] : 'No description provided';
    $options[$id] = $datas['title'] . ' - <em>' . $description . '</em>';
  }

  $form['okcdesign']['plugins']['okcdesign_plugins_enabled'] = array(
    '#type' => 'checkboxes',
    '#title' => t('Enable'),
    '#description' => t('Apdapt drupal html markup to foundation css'),
    '#options' => $options,
    '#default_value' => theme_get_setting('okcdesign_plugins_enabled')
  );



}