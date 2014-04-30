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
    '#description' => t('Enable additionnals plugins for your theme'),
  );

  $options = array();
  $required_plugins = array();
  foreach($plugins = okcdesign_get_plugins() as $id => $datas) {
    $required_by = okcdesign_check_plugin_dependencies($id);
    $description = isset($datas['description']) ? $datas['description'] : 'No description provided';
    if ($required_by) {
      $required_plugins[] = $id;
      $description .= '<br/><strong>required by</strong> : ' . implode(', ', $required_by);
    }
    if (isset($datas['dependencies'])) {
      $description .= "<br /><strong> depends on</strong> : " . implode(', ', $datas['dependencies']);
    }
    $options[$id] = $datas['title'] . ' - ' .  $description . ' <hr />';
  }

  $form['okcdesign']['plugins']['okcdesign_plugins_enabled'] = array(
    '#type' => 'checkboxes',
    '#title' => t('Enable'),
    '#options' => $options,
    '#default_value' => theme_get_setting('okcdesign_plugins_enabled')
  );
  // disabled plugin that are required by other plugins
  foreach ($required_plugins as $plugin) {
    //$form['okcdesign']['plugins']['okcdesign_plugins_enabled'][$plugin] = array('#disabled' => 'disabled');
  }



}

