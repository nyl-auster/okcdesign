<?php

/**
 * implements hook_form_FORM_ID_alter()
 *
 * Plugins configuration form.
 *
 * Reorganize theme settings in vertical tabs.
 * Puts plugins tab in first.
 *
 * @param $form
 * @param $form_state
 */
function okcdesign_form_system_theme_settings_alter(&$form, $form_state) {

  // we'll need plugin API to build administration plugins form.
  require_once 'theme_plugins_manager.php';

  // some plugins and fields will be hidden, except if we add "expert" in at the end of url.
  $expert = arg(4) == 'expert' ? TRUE : FALSE;

  $form['#submit'][] = 'okcdesign_plugins_form_submit';

  // create vertical tabs to organize theme settings form.
  // put our plugin tab first as it may be more frequently used than general drupal settings.
  $form['okcdesign'] = array(
    '#type' => 'vertical_tabs',
    '#weight' => -10
  );

  // put back Drupal settings in a "General settings" vertical tab
  $form['okcdesign']['general'] = array(
    '#type' => 'fieldset',
    '#title' => t('&nbsp;&nbsp; Drupal core Settings'),
  );
  foreach (array('theme_settings', 'logo', 'favicon') as $field) {
    if (isset($form[$field])) {
      $form['okcdesign']['general'][$field] =  $form[$field];
      unset($form[$field]);
    }
  }

  // get all declared plugins in theme_plugins_registry.php file.
  $plugins = theme_get_plugins();

  // Build individual "checkbox" FAPI element, as they are more flexible thant "checkboxes" FAPI element type.
  foreach($plugins as $plugin_id => $plugin_datas) {

    // add a different symbol if plugin is enabled or disabled.
    $state = theme_plugin_is_enabled($plugin_id) ? '&#10003;': "&#10008;";

    // create a vertical by plugin
    $form['okcdesign'][$plugin_id] = array(
      '#type' => 'fieldset',
      '#access' => !$expert && !empty($plugin_datas['expert']) ? FALSE : TRUE,
      '#title' => $state . ' ' . $plugin_datas['title']
    );

    // add a checkbox to enable the plugin
    $form['okcdesign'][$plugin_id]["theme_plugin_$plugin_id"] = _okcdesign_build_plugin_checkbox($plugin_id, $plugins);

    // add configuration form for this plugin, if available.
    $plugin = new $plugin_id();
    if (!theme_plugin_is_enabled($plugin_id)) {
      $form['okcdesign'][$plugin_id]["theme_plugin_$plugin_id"]['#description'] .= "<br/>Enable to see configuration form for this plugin, if any";
    }
    else if ( method_exists($plugin, 'settings_form') && $plugin->settings_form($form)) {
      $form['okcdesign'][$plugin_id]["settings_$plugin_id"] = array('#type' => 'fieldset');
      $form['okcdesign'][$plugin_id]["settings_$plugin_id"]["theme_plugin_settings_$plugin_id"] = $plugin->settings_form($form);
      // retain configuration in theme settings as an array for this specifig plugin.
      $form['okcdesign'][$plugin_id]["settings_$plugin_id"]["theme_plugin_settings_$plugin_id"]['#tree'] = TRUE;
    }

  }

}

/**
 * Build a checkbox to enable / disable a plugin.
 *
 * @FIXME A plugin should not be disabled if it is required by another plugin.
 *   Do not use "disabled" html attribute, as values are not POSTed anymore and
 *   so configuration is not saved correctly in this case.
 * @param $plugin_id
 *   plugin id
 * @param $plugins
 *   all registered plugins. Needed to display informations about plugin dependecies.
 * @return array
 *   checkbox FAPI element
 */
function _okcdesign_build_plugin_checkbox($plugin_id, $plugins) {

  $plugin = $plugins[$plugin_id];

  // Fetch all plugins which required this plugin to work as expected
  $required_by_plugins = array();
  // this array will contain only *enabled* required plugins.
  $required_by_enabled_plugins = array();
  foreach ($plugin['required_by_plugins'] as $required_by_plugin_id => $required_by_plugin) {
    $required_by_plugins[$required_by_plugin_id] = $required_by_plugin['title'];
    if (theme_plugin_is_enabled($required_by_plugin_id)) {
      $required_by_plugins[$required_by_plugin_id] = $required_by_plugin['title'] . '<strong style="color:green;"> (enabled) </strong>';
      $required_by_enabled_plugins[$required_by_plugin_id] = $required_by_plugin;
    }
    else {
      $required_by_plugins[$required_by_plugin_id] = $required_by_plugin['title'] . ' <strong style="color:red"> (disabled) </strong>';
    }
  }

  // Find all plugins our plugin need to work.
  $dependencies = array();
  $dependencies_disabled = array();
  if (isset($plugin['dependencies'])) {
    foreach($plugin['dependencies'] as $dependencie_id) {
      if (theme_plugin_is_enabled($dependencie_id)) {
        $dependencies[$dependencie_id] = $plugins[$dependencie_id]['title'] . '<strong style="color:green"> (enabled) </strong>';
      }
      else {
        $dependencies[$dependencie_id] = $plugins[$dependencie_id]['title'] . ' <strong style="color:red;"> (disabled) </strong>';
        $dependencies_disabled[$dependencie_id] =$plugins[$dependencie_id]['title'];
      }
    }
  }

  // build a title for the checkbox, adding description and all dependencies informations.
  $title = "<strong> ENABLE PLUGIN " . strtoupper($plugin['title']) . "</strong>";

  $description  = !empty($plugin['description']) ?  $plugin['description'] . '<br />' : '';
  if ($required_by_plugins) {
    $description .= '<br/> <strong>Required by : </strong>' . implode(', ', $required_by_plugins) . '<br />';
  }
  if ($dependencies) {
    $description .= '<br/> <strong>Depends on : </strong>' . implode(', ', $dependencies) . '<br />';
  }

  // create checkbox to enable / disabled this plugin.
  $checkbox = array(
    '#type' => 'checkbox',
    '#title' => $title,
    '#default_value' => theme_get_setting("theme_plugin_$plugin_id"),
    '#description' => $description,
  );

  // do not allow to disable a plugin if enabled plugins require it
  if ($required_by_enabled_plugins) {
    $fake_checkbox = $checkbox;
    $fake_checkbox['#disabled'] = FALSE;
    $checkbox['#access'] = FALSE;
    return $fake_checkbox;
  }

  // do not allow to Enable a plugin if there is some missing dependencies
  if ($dependencies_disabled) {
    $fake_checkbox = $checkbox;
    $fake_checkbox['#disabled'] = FALSE;
    $fake_checkbox['#title'] .= ' - Please enable missing dependencies to enable this plugin ! ';
    $checkbox['#access'] = FALSE;
    return $fake_checkbox;
  }
  return $checkbox;
}

/**
 * Additionnal submit callback for theme settings form.
 *
 * @param $form
 * @param $form_state
 */
function okcdesign_plugins_form_submit($form, $form_state) {
  // make sure new themes provided by plugins are discovered.
  cache_clear_all('theme_registry', 'cache', TRUE);
  drupal_set_message('Theme registry rebuild');
}


