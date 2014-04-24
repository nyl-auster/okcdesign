<?php

function okcdesign_form_system_theme_settings_alter(&$form, $form_state) {

  include 'inc/okcdesign_plugins_manager.php';

  $form['okcdesign'] = array(
    '#type' => 'vertical_tabs',
    '#weight' => -10,
  );

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
    '#description' => t('Theme plugins.'),
    '#options' => $options,
    '#default_value' => theme_get_setting('okcdesign_plugins_enabled')
  );



}