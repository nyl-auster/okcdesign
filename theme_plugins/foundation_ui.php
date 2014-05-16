<?php

/**
 * Add an ui to change settings of foundation.
 * phpscss library is used to compile all scss to css in files/okcdesign/{themeName}
 * Class foundation_ui
 */
class foundation_ui extends theme_plugin {

  /**
   * Plugin configuration form. Let user choose which menus he wants
   * to print in the main topbar.
   */
  function settings_form(&$theme_settings_form) {

    $theme_settings_form['#submit'][] = 'foundation_ui_settings_form_submit';

    // drupal_add_js($this->base_theme_path . '/' . $this->vendors_directory . '/jquery/dist/jquery.min.js');
    // drupal_add_js("jQuery(document).ready(function () {
    //  jQuery('#edit-theme-plugin-settings-foundation-ui-primary-color').colorpicker();
    //  alert('ok');
    // })",
    // array('type' => 'inline', 'scope' => 'footer'));
    // form id match exactly foundation settings names.
    $form['row-width'] = array(
      '#type' => 'textfield',
      '#title' => 'Grid row width',
      '#default_value' => theme_plugin_get_setting(__CLASS__, 'row-width', ''),
    );
    $form['total-columns'] = array(
      '#type' => 'textfield',
      '#title' => 'Grid columns number',
      '#default_value' => theme_plugin_get_setting(__CLASS__, 'total-columns', ''),
    );
    $form['primary-color'] = array(
      '#type' => 'textfield',
      '#title' => 'Primary color',
      '#default_value' => theme_plugin_get_setting(__CLASS__, 'primary-color', ''),
    );
    $form['secondary-color'] = array(
      '#type' => 'textfield',
      '#title' => 'Secondary color',
      '#default_value' => theme_plugin_get_setting(__CLASS__, 'secondary-color', ''),
    );
    $form['header-font-color'] = array(
      '#type' => 'textfield',
      '#title' => 'h1, h2, etc... font colors',
      '#default_value' => theme_plugin_get_setting(__CLASS__, 'header-font-color', ''),
    );
    return $form;
  }

}

function foundation_ui_settings_form_submit($form, $form_state) {

  if (empty($form_state['values']['theme_plugin_settings_foundation_ui'])) return;

  $new_settings = '';
  foreach ($form_state['values']['theme_plugin_settings_foundation_ui'] as $key => $value) {
    if (trim($value)) {
      $new_settings .= "$$key: $value;\r\n";
    }
  }

  // create required directories if they don't exist
  $okcdesign_custom_scss_path = variable_get('file_public_path', conf_path() . '/files') . '/okcdesign';
  if (!is_readable($okcdesign_custom_scss_path)) {
    mkdir($okcdesign_custom_scss_path);
  }
  $okcdesign_theme_custom_theme_scss_path = $okcdesign_custom_scss_path . '/' . arg(3);
  if (!is_readable($okcdesign_theme_custom_theme_scss_path)) {
    mkdir($okcdesign_theme_custom_theme_scss_path);
  }

  $okcdesign_theme_custom_theme_scss_file = $okcdesign_theme_custom_theme_scss_path . '/_theme_custom_settings.scss';

  $old_settings = file_get_contents($okcdesign_theme_custom_theme_scss_file);

  // settings have not changed, stop here.
  if ($new_settings == $old_settings) return;

  // else, overwrite with new settings.
  file_put_contents($okcdesign_theme_custom_theme_scss_file, $new_settings);

  if (is_readable($okcdesign_theme_custom_theme_scss_file)) {

    require_once drupal_get_path('theme', 'okcdesign') . "/bower_components/scssphp/scss.inc.php";

  // we have to recompile ALL scss file if user what to customize scss.
    $default_theme_path = drupal_get_path('theme', variable_get('theme_default'));
    $base_theme_path = drupal_get_path('theme', 'okcdesign');
    $app_scss = "$default_theme_path/scss/app.scss";

  // configure import pathes for our php scss compilator.
    $import_paths = array(
      "$base_theme_path/bower_components/foundation/scss",
      "$base_theme_path/scss",
      $okcdesign_theme_custom_theme_scss_path,
    );
    $scss = new scssc();
    $scss->setImportPaths($import_paths);
    $scss_to_compile = file($app_scss);;
    $scss_to_compile = _foundation_ui_rebuild_scss_import_file($scss_to_compile);
    $app_css = $scss->compile($scss_to_compile);
    file_put_contents($okcdesign_theme_custom_theme_scss_path . '/app.css', $app_css);
    drupal_set_message("All scss files have been recompiled to css files with new settings.");
  }


}

/**
 * get scss/app.scss file, and add @import of "theme_custom_settings.scss" just after @import "settings",
 * a fill that will be located in files/okcdesign/{active_theme} directory.
 * This is where we store theme custom overrides of _settings.scss variables.
 *
 * @param $lines
 * @return string
 */
function _foundation_ui_rebuild_scss_import_file($lines) {
  $file = '';
  foreach ($lines as $i => $line) {
    $file .= $line . "\r\n";
    $cleaned_line = str_replace(' ', '', $line);
    if(strpos($cleaned_line, "@import'settings'") !== FALSE || strpos($cleaned_line, '@import"settings"') !== FALSE) {
      $file .= '@import "theme_custom_settings";' . "\r\n";
    }
  }
  return $file;
}