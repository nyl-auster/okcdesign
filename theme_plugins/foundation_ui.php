<?php

/**
 * Add an ui to change _settings.scss variables of foundation.
 *
 * phpscss library is used to compile all scss to css in files/okcdesign/{themeName}
 * Class foundation_ui
 */
class foundation_ui extends theme_plugin {

  /**
   * Plugin configuration form. Let user choose which menus he wants
   * to print in the main topbar.
   */
  function settings_form(&$theme_settings_form = array()) {

    $theme_settings_form['#submit'][] = 'foundation_ui_settings_form_submit';

    $expert = arg(4) == 'expert' ? TRUE : FALSE;

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
      '#default_value' => theme_plugin_get_setting(__CLASS__, 'row-width', '1000px'),
      '#description' => "Any valid css unit. Note that foundation will try to convert this to rem",
    );
    $form['total-columns'] = array(
      '#access' => $expert,
      '#type' => 'textfield',
      '#title' => 'Grid columns number',
      '#default_value' => theme_plugin_get_setting(__CLASS__, 'total-columns', '12'),
      '#description' => "Number of columns for the grid. Enable grid viewer plugin to display the grid in your theme.",
    );
    $form['primary-color'] = array(
      '#type' => 'textfield',
      '#title' => 'Primary color',
      '#default_value' => theme_plugin_get_setting(__CLASS__, 'primary-color', '#008CBA'),
      '#description' => 'Any valid css color.',
    );
    $form['secondary-color'] = array(
      '#type' => 'textfield',
      '#title' => 'Secondary color',
      '#default_value' => theme_plugin_get_setting(__CLASS__, 'secondary-color', '#e7e7e7'),
      '#description' => 'Any valid css color.',
    );
    $form['header-font-color'] = array(
      '#type' => 'textfield',
      '#title' => 'h1, h2, etc... font colors',
      '#default_value' => theme_plugin_get_setting(__CLASS__, 'header-font-color', '#222'),
      '#description' => 'Any valid css color.',
    );
    $form['alert-color'] = array(
      '#type' => 'textfield',
      '#title' => 'Alert colors',
      '#default_value' => theme_plugin_get_setting(__CLASS__, 'alert-color', '#f04124'),
      '#description' => 'Any valid css color.',
    );
    $form['success-color'] = array(
      '#type' => 'textfield',
      '#title' => 'Success colors',
      '#default_value' => theme_plugin_get_setting(__CLASS__, 'success-color', '#43AC6A'),
      '#description' => 'Any valid css color.',
    );
    $form['warning-color'] = array(
      '#type' => 'textfield',
      '#title' => 'Warning colors',
      '#default_value' => theme_plugin_get_setting(__CLASS__, 'warning-color', '#f08a24'),
      '#description' => 'Any valid css color.',
    );
    $form['info-color'] = array(
      '#type' => 'textfield',
      '#title' => 'Info colors',
      '#default_value' => theme_plugin_get_setting(__CLASS__, 'info-color', '#a0d3e8'),
      '#description' => 'Any valid css color.',
    );
    $form['ancho-font-color'] = array(
      '#type' => 'textfield',
      '#title' => 'Links colors',
      '#default_value' => theme_plugin_get_setting(__CLASS__, 'anchor-font-color', '#008CBA'),
      '#description' => 'Any valid css color.',
    );
    $form['ancho-font-color-hover'] = array(
      '#type' => 'textfield',
      '#title' => 'Links colors hover',
      '#default_value' => theme_plugin_get_setting(__CLASS__, 'anchor-font-color-hover', 'scale-color($primary-color, $lightness: -14%)'),
      '#description' => 'Any valid css color.',
    );

    $form['expert'] = array(
      '#access' => $expert,
      '#type' => 'textarea',
      '#title' => "Write custom valid scss here",
      '#default_value' => theme_plugin_get_setting(__CLASS__, 'expert', ''),
      '#description' => 'Free scss for expert.',
    );

    return $form;
  }

}

/**
 * Prepare user submited values before saving them.
 * @param $values
 */
function foundation_ui_prepare_values($values) {
  foreach ($values as $key => $value) {
    if ($key == 'row-width') {
      $values[$key] = "rem-calc($value)";
    }
    // remove ";" if any, they are automatically added when saving values.
    $values[$key] = str_replace(';', '', $value);
  }
  return $values;
}

/**
 * Recompile all theme scss on plugin form submission.
 * @param $form
 * @param $form_state
 */
function foundation_ui_settings_form_submit($form, $form_state) {

  // no values submitted for custom scss settings, abort.
  if (empty($form_state['values']['theme_plugin_settings_foundation_ui'])) return;

  $theme = arg(3);
  $themepath = drupal_get_path('theme', $theme);

  // create directories, if they do not exist, to store submitted scss.
  $result = foundation_ui_create_files_directories($theme);
  if (!$result) {
    drupal_set_message('error', 'Directories creation failed. Please check your files directory permissions and retry');
  }

  // Create a valid scss string from submitted values.
  $values = foundation_ui_prepare_values($form_state['values']['theme_plugin_settings_foundation_ui']);
  $new_scss = foundation_ui_create_scss_string_from_array($values);

  // if submitted scss settings are the same than scss settings already stored in file, do nothing more.
  $old_scss = foundation_ui_get_theme_custom_scss($theme);
  if ($new_scss == $old_scss) return;

  // store scss in a file.
  $result = file_put_contents(foundation_ui_get_theme_scss_file_path($theme), $new_scss);
  if (!$result) {
    drupal_set_message('error', 'Fail to write custom scss in a file, please check your files directory permissions and retry.');
  }

  // get app.scss from this theme.
  $app_scss_content = file($themepath . '/scss/app.scss');
  // add @import "user_settings" to app.scss content, just after @import "settings" line.
  $scss_string = foundation_ui_rebuild_scss_import_file($app_scss_content);

  // let phpscss compile this file content to css
  $css = foundation_ui_compile_theme_scss($theme, $scss_string);
  // store compiled css into a files/okcdesign/{theme_name}/app.css file.

  // save compiled css to a file, that will be served by foundation plugin if it can load it.
  $result = file_put_contents(foundation_ui_get_theme_css_file_path($theme), $css);
  if (!$result) {
    drupal_set_message('error', 'Fail to write custom scss in a file, please check your files directory permissions and retry.');
  }

  drupal_set_message("All scss files have been recompiled to css files with new settings.");

}

/*============
  HELPERS
 =============*/

/**
 * Return name of global directory used in files directory to store custom scss of all themes.
 * Each theme will have its own subdirectory.
 * @return string
 */
function foundation_ui_get_okcdesign_files_directory_name() {
  return 'okcdesign';
}

/**
 * Get name of file used to store custom scss.
 * @return string
 */
function foudation_ui_get_custom_scss_filename() {
  return 'user_settings.scss';
}

/**
 * Get name of file used to store custom css.
 * @return string
 */
function foudation_ui_get_custom_css_filename() {
  return 'user_app.css';
}

/**
 * Directory used in files directory, to store foundation_ui custom scss files.
 * @return string
 */
function foundation_ui_get_okcdesign_custom_directory_path() {
  return variable_get('file_public_path', conf_path() . '/files') . '/' . foundation_ui_get_okcdesign_files_directory_name();
}

/**
 * Directory used in files, to store foundation_ui custom files for a specific theme.
 * @param string $themename
 * @return string
 */
function foundation_ui_theme_directory_path($themename) {
  return foundation_ui_get_okcdesign_custom_directory_path() . '/' . $themename;
}

/**
 * Location of file where user-defined settings are stored for a specific theme.
 * @param string $themename
 * @return string
 */
function foundation_ui_get_theme_scss_file_path($themename) {
  return foundation_ui_theme_directory_path($themename) . '/' . foudation_ui_get_custom_scss_filename();
}

/**
 * Location of file where all theme compiled css are stored.
 * @param string $themename
 * @return string
 */
function foundation_ui_get_theme_css_file_path($themename) {
  return foundation_ui_theme_directory_path($themename) . '/' . foudation_ui_get_custom_css_filename();
}

/**
 * Return chmod for created directories and files by this plugin.
 * Others main only read and go through those directories.
 * @return int
 */
function foundation_ui_chmod() {
  return 0775;
}

/**
 * @param string $themename : The themename we want to create custom settings for.
 * @return bool : FALSE if one of the directories has not been successfully created, TRUE otherwise.
 */
function foundation_ui_create_files_directories($themename) {

  $okcdesign_files_directory = foundation_ui_get_okcdesign_custom_directory_path();
  $theme_custom_directory = foundation_ui_theme_directory_path($themename);

  // remove umask, as it may disallow us to add a "write" permission
  // for the group; and we need it.
  $old_umask = umask(0);

  // create okcdesign files directory if it does not exist yet.
  if (!file_exists($okcdesign_files_directory)) {
    $result = mkdir($okcdesign_files_directory, foundation_ui_chmod());

    if ($result == FALSE) return $result;
  }
  // create okcdesign theme custom directory if it does not exist yet.
  if (!file_exists($theme_custom_directory)) {
    $result = mkdir($theme_custom_directory, foundation_ui_chmod());
    if ($result == FALSE) return $result;
  }
  // put back umask
  umask($old_umask);
  return TRUE;

}

/**
 * @param array $values
 *   associative array of the following form :
 *   scss_variable_name => value
 *
 * E.g : array('primary-color' => 'blue', ...)
 * @return string
 *   valid scss string, e.g :
 *   $primary-color: blue;
 *   ...
 */
function foundation_ui_create_scss_string_from_array($values) {
  $scss_string = '';
  foreach ($values as $key => $value) {
    if (trim($value) && $key != 'expert') {
      $scss_string .= "$$key: $value;\r\n";
    }
  }
  if (!empty($values['expert'])) {
    $scss_string .= $values['expert'];
  }
  return $scss_string;
}

/**
 * Return scss string of user-defined scss for a specific theme.
 * @param $themename
 * @return string
 */
function foundation_ui_get_theme_custom_scss($themename) {
  $scss = '';
  $filepath = foundation_ui_get_theme_scss_file_path($themename);
  if (file_exists($filepath)) {
    $scss = file_get_contents(foundation_ui_get_theme_scss_file_path($themename));
  }
  return $scss;
}

/**
 * get {theme_name}scss/app.scss file, and add @import of "_custom_settings.scss" just after @import "settings",
 * This is where we store theme custom overrides of _settings.scss variables.
 *
 * @param array $lines
 *   an array of scss lines, as returned by "file" function in php.
 * @return string
 */
function foundation_ui_rebuild_scss_import_file($lines) {
  $file = '';
  foreach ($lines as $line) {
    $file .= $line . "\r\n";
    $cleaned_line = str_replace(' ', '', $line);
    if(strpos($cleaned_line, "@import'settings'") !== FALSE || strpos($cleaned_line, '@import"settings"') !== FALSE) {
      $file .= '@import "' . foudation_ui_get_custom_scss_filename() . '";' . "\r\n";
    }
  }
  return $file;
}

/**
 * Load our php compiler for scss.
 * @param array $options
 * @return scssc
 */
function foundation_ui_get_scss_compiler($options = array()) {
  require_once  drupal_get_path('theme', 'okcdesign') . '/bower_components/scssphp/scss.inc.php';
  return new scssc($options);
}

/**
 * Compile a scss string for a specific theme with phpscss compiler.
 * This only convert a scss string to a css string, setting import paths
 * correctly for our okcdesign theme and subthemes.
 *
 * @param string $themename : The theme we want to recompile all the scss for.
 * @param string $scss_string : a valid scss string to compile.
 * @return string : A valid css string, ready to be stored in a file.
 */
function foundation_ui_compile_theme_scss($themename, $scss_string) {

  $okcdesign_theme_path = drupal_get_path('theme', 'okcdesign');

  // configure import paths for our php scss compilator.
  // Important : we add an import path to our custom theme scss directory, containing
  // scss defined by user from admin settings page.
  $import_paths = array(
    "$okcdesign_theme_path/bower_components/foundation/scss",
    "$okcdesign_theme_path/scss",
    foundation_ui_theme_directory_path($themename),
  );

  $scss = foundation_ui_get_scss_compiler();
  $scss->setImportPaths($import_paths);

  $css_string = $scss->compile($scss_string);
  return $css_string;

}






