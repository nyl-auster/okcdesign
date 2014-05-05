<?php

function okcdesign_form_system_theme_settings_alter(&$form, $form_state) {

  include 'theme_plugins_manager/theme_plugins_manager.php';

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
    '#description' => t('Enable additionnals plugins for your theme'),
  );

  $options = array();
  foreach($plugins = theme_get_plugins() as $id => $datas) {
    $package = isset($datas['package']) ? $datas['package'] : 'others';
    $form['okcdesign']['plugins'][$package]['#title'] = $datas['package'];
    $form['okcdesign']['plugins'][$package]['#type'] = 'fieldset';
    $form['okcdesign']['plugins'][$package]["theme_plugin_$id"] = array(
      '#type' => 'checkbox',
      '#title' => _theme_options_title($datas),
      '#default_value' => theme_get_setting("theme_plugin_$id"),
    );
  }

}

function _theme_options_title($plugin) {

  $plugins = theme_get_plugins();

  $required_by_plugins = array();
  foreach ($plugin['required_by_plugins'] as $required_by_plugin) {
    $required_by_plugins[] = $required_by_plugin['title'];
  }

  $dependencies = array();
  if (isset($plugin['dependencies'])) {
    foreach($plugin['dependencies'] as $id) {
       $dependencies[] = $plugins[$id]['title'];
    }
  }

  $description = $plugin['description'] ? $plugin['description'] : 'No description provided';

  if ($required_by_plugins) {
    $description .= '<br/> <strong>Required by : </strong>' . implode(', ', $required_by_plugins);
  }

  if ($dependencies) {
    $description .= '<br/> <strong>Depends on: </strong>' . implode(', ', $dependencies);
  }

  $html = '';
  $html .= $plugin["title"] . ' - ' . $description;
  return $html;
}


