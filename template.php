<?php
/**
 * @file
 * Template.php
 *
 * No custom code should be written here, only invocations
 * to plugin via theme_plugins_manager.
 *
 * @see theme_plugins_manager/theme_plugins_manager.php
 */

// load plugins system
include_once 'theme_plugins_manager/theme_plugins_manager.php';

// all this things happen when submitting theme settings form.

/*
$okcdesign_custom_scss_path = variable_get('file_public_path', conf_path() . '/files') . '/okcdesign';
if (!is_readable($okcdesign_custom_scss_path)) {
  mkdir($okcdesign_custom_scss_path);
}
$okcdesign_theme_custom_theme_scss_path = $okcdesign_custom_scss_path . '/' . variable_get("theme_default");
if (!is_readable($okcdesign_theme_custom_theme_scss_path)) {
  mkdir($okcdesign_theme_custom_theme_scss_path);
}
$okcdesign_theme_custom_theme_scss_file = $okcdesign_theme_custom_theme_scss_path . '/_theme_custom_settings.scss';
if (!is_readable($okcdesign_theme_custom_theme_scss_file)) {
  file_put_contents($okcdesign_theme_custom_theme_scss_file, '');
}

if (is_readable($okcdesign_theme_custom_theme_scss_file)) {

  require "bower_components/scssphp/scss.inc.php";

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
  $scss_to_compile = _scssphp_buil_scss_import_file($scss_to_compile);
  $app_css = $scss->compile($scss_to_compile);
  file_put_contents($okcdesign_theme_custom_theme_scss_path . '/app.css', $app_css);
}
*/

/**
 * Rebuild app.scss file, but add an import of "theme_custom_settings.scss"
 * that lives in files/okcdesign/{active_theme}
 * @param $lines
 * @return string
 */
function _scssphp_buil_scss_import_file($lines) {
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


/*=============================
   HOOKS
 ==============================*/

/**
 * Implements hook_theme()
 */
function okcdesign_theme() {
  $themes = array();
  theme_plugins_invoke(__FUNCTION__, $themes);
  return $themes;
}

/*=============================
   PREPROCESS
 ==============================*/

/**
 * Implements template_preprocess_page()
 */
function okcdesign_preprocess_page(&$variables) {
  theme_plugins_invoke(__FUNCTION__, $variables);
}

/**
 * Implements hook_css_alter()
 */
function okcdesign_css_alter(&$variables) {
  theme_plugins_invoke(__FUNCTION__, $variables);
}

/**
 * Implements hook_html_head_alter().
 */
function okcdesign_html_head_alter(&$variables) {
  theme_plugins_invoke(__FUNCTION__, $variables);
}

/**
 * Implements template_preprocess_foundation_topbar()
 */
function okcdesign_preprocess_foundation_topbar(&$variables) {
  theme_plugins_invoke(__FUNCTION__, $variables);
}

/*=============================
   THEME OVERRIDES
 ==============================*/

/**
 * Implements hook_theme_breadcrumb()
 */
function okcdesign_breadcrumb($variables) {
  $html = theme_plugins_invoke(__FUNCTION__, $variables);
  if ($html) return $html;
  return theme_breadcrumb($variables);
}

function okcdesign_status_messages($variables) {
  $html = theme_plugins_invoke(__FUNCTION__, $variables);
  if ($html) return $html;
  return theme_status_messages($variables);
}
