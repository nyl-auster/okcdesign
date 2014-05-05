<?php

function okcdesign_form_system_theme_settings_alter(&$form, $form_state) {

  include 'theme_plugins_manager/theme_plugins_manager.php';

  $form['okcdesign'] = array(
    '#type' => 'vertical_tabs',
    '#weight' => -10,
  );



  $form['okcdesign']['plugins'] = array(
    '#type' => 'fieldset',
    '#title' => t('Theme plugins'),
    '#description' => t('Enable additionnals plugins for your theme'),
  );


    $plugins = theme_get_plugins();
  // we build individual checkboxes because it is easier for us to disabled
  // checkboxes from required plugins this way with drupal FAPI.
  foreach($plugins as $id => $datas) {

    $package = isset($datas['package']) ? $datas['package'] : 'others';
    $form['okcdesign']['plugins'][$package]['#title'] = $datas['package'];
    $form['okcdesign']['plugins'][$package]['#type'] = 'fieldset';
    $form['okcdesign']['plugins'][$package]["theme_plugin_$id"] = _okcdesign_build_checkbox($id, $datas, $form);

    $object = new $id;
    if (method_exists($object, 'settings_form')) {
      $form['okcdesign']['plugins'][$package]["settings_$id"] =  array(
        '#title' => "Configure " . $datas['title'],
        '#type' => 'fieldset',
        '#collapsible' => TRUE,
        '#collapsed' => TRUE,
      );
      $form['okcdesign']['plugins'][$package]["settings_$id"]["theme_plugin_settings_$id"] = $object->settings_form();
      $form['okcdesign']['plugins'][$package]["settings_$id"]["theme_plugin_settings_$id"]['#tree'] = TRUE;
    }
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

}

function _okcdesign_build_checkbox($id, $plugin) {

  $plugins = theme_get_plugins();

  $required_by_plugins = array();
  foreach ($plugin['required_by_plugins'] as $required_by_plugin) {
    $required_by_plugins[] = $required_by_plugin['title'];
  }

  $dependencies = array();
  if (isset($plugin['dependencies'])) {
    foreach($plugin['dependencies'] as $dependencie_id) {
      $dependencies[] = $plugins[$dependencie_id]['title'];
    }
  }

  $title = $plugin['title'];
  $title .= isset($plugin['description']) ? ' - ' . $plugin['description'] : ' - No description provided';

  if ($required_by_plugins) {
    $title .= '<br/> <strong>Required by : </strong>' . implode(', ', $required_by_plugins);
  }

  if ($dependencies) {
    $title .= '<br/> <strong>Depends on: </strong>' . implode(', ', $dependencies);
  }

  $checkbox = array(
    '#type' => 'checkbox',
    '#title' => $title,
    '#default_value' => theme_get_setting("theme_plugin_$id"),

  );

  // do not allow user to disabled a plugin that is required by others plugins
  if ($required_by_plugins || !empty($plugin['required'])) {
    $checkbox['#disabled'] = TRUE;
    $checkbox['#title'] .= "<br /> This plugin is required by the theme and can not be disabled";
  }

  return $checkbox;
}


