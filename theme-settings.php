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
  include_once 'theme_plugins_manager/theme_plugins_manager.php';

  // some plugins will be hide, except if we add "expert" in at the end of url.
  $mode = arg(4) == 'expert' ? 'expert' : 'simple';

  $form['#submit'][] = 'okcdesign_plugins_form_submit';

  // create vertical tabs to organize theme settings form.
  // put our plugin tab first as it may be more frequently used than general drupal settings.
  $form['okcdesign'] = array(
    '#type' => 'vertical_tabs',
    '#weight' => -10
  );

  $form['okcdesign']['plugins'] = array(
    '#type' => 'fieldset',
    '#title' => t('Theme plugins'),
    '#description' => t('Enable additionnals plugins for your theme'),
  );

  // get all declared plugins in okcdesign.info.plugins.php file.
  $plugins = theme_get_plugins();

  // Build individual "checkbox" FAPI element, as they are more flexible thant "checkboxes" FAPI element type.
  foreach($plugins as $plugin_id => $plugin_datas) {

    // group plugins in fieldset by their package.
    $package = isset($plugin_datas['package']) ? $plugin_datas['package'] : 'others';

    // Put each plugin inside a package group (using fieldset)
    $form['okcdesign']['plugins'][$package]['#title'] = $plugin_datas['package'];
    $form['okcdesign']['plugins'][$package]['#type'] = 'fieldset';

    // build a plugin checkbox
    $form['okcdesign']['plugins'][$package]["theme_plugin_$plugin_id"] = _okcdesign_build_plugin_checkbox($plugin_id, $plugins);

    // add plugin settings form, if any, below the checkbox to enable it
    $plugin = $plugin_id::get_instance();
    if (method_exists($plugin, 'settings_form') && $plugin->settings_form($form)) {
      $form['okcdesign']['plugins'][$package]["settings_$plugin_id"] =  array(
        '#title' => "Configure " . $plugin_datas['title'],
        '#type' => 'fieldset',
        '#collapsible' => TRUE,
        '#collapsed' => TRUE,
      );
      $form['okcdesign']['plugins'][$package]["settings_$plugin_id"]["theme_plugin_settings_$plugin_id"] = $plugin->settings_form($form);
      // retain configuration in theme settings as an array for this specifig plugin.
      $form['okcdesign']['plugins'][$package]["settings_$plugin_id"]["theme_plugin_settings_$plugin_id"]['#tree'] = TRUE;
    }


    // just an html divider to make plugin administration more readable.
    $form['okcdesign']['plugins'][$package][$plugin_id]['divider'] = array(
      '#type' => 'item',
      '#markup' => "<hr/>"
    );

    // hide "expert" plugins for normal user.
    if ($mode != 'expert' && !empty($plugin_datas['expert'])) {
      $form['okcdesign']['plugins'][$package]["settings_$plugin_id"]['#access'] = FALSE;
      $form['okcdesign']['plugins'][$package]["theme_plugin_$plugin_id"]['#access'] = FALSE;
      $form['okcdesign']['plugins'][$package][$plugin_id]['divider'] = FALSE;
    }

  }

  // put back Drupal settings in a "General settings" vertical tab
  $form['okcdesign']['general'] = array(
    '#type' => 'fieldset',
    '#title' => t('General Settings'),
  );
  foreach (array('theme_settings', 'logo', 'favicon') as $field) {
    if (isset($form[$field])) {
      $form['okcdesign']['general'][$field] =  $form[$field];
      unset($form[$field]);
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
  foreach ($plugin['required_by_plugins'] as $required_by_plugin) {
    $required_by_plugins[] = $required_by_plugin['title'];
  }

  // Find all plugins our plugin need to work.
  $dependencies = array();
  if (isset($plugin['dependencies'])) {
    foreach($plugin['dependencies'] as $dependencie_id) {
      $dependencies[] = $plugins[$dependencie_id]['title'];
    }
  }

  // build a title for the checkbox, adding description and all dependencies informations.
  $description = '';
  $title = "<strong>" . strtoupper($plugin['title']) . "</strong>";
  if (isset($plugin['description'])) {
    $title .= " - " . $plugin['description'];
  }
  if ($required_by_plugins) {
    $description .= '<br/> <strong>Required by : </strong>' . implode(', ', $required_by_plugins);
  }
  if ($dependencies) {
    $description .= '<br/> <strong>Depends on: </strong>' . implode(', ', $dependencies);
  }

  // create checkbox to enable / disabled this plugin.
  $checkbox = array(
    '#type' => 'checkbox',
    '#title' => $title,
    '#default_value' => theme_get_setting("theme_plugin_$plugin_id"),
    '#description' =>  $description,
  );

  // @TODO do not allow to disable a plugin required by enabled plugins.

  return $checkbox;
}

/**
 * Additionnal submit callback for theme settings form.
 * @param $form
 * @param $form_state
 */
function okcdesign_plugins_form_submit($form, $form_state) {
  // make sure new themes provided by plugins are discovered.
  cache_clear_all('theme_registry', 'cache', TRUE);
  drupal_set_message('Theme registry rebuild');
}


