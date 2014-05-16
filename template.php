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


$okcdesign_custom_scss_path = variable_get('file_public_path', conf_path() . '/files') . '/okcdesign-scss';
if (!is_readable($okcdesign_custom_scss_path)) {
  mkdir($okcdesign_custom_scss_path);
}
$okcdesign_theme_custom_theme_scss_path = $okcdesign_custom_scss_path . '/' . variable_get("theme_default");
if (!is_readable($okcdesign_theme_custom_theme_scss_path)) {
  mkdir($okcdesign_theme_custom_theme_scss_path);
}
$okcdesign_theme_custom_theme_scss_file = $okcdesign_theme_custom_theme_scss_path . '/user-defined-settings.scss';
if (!is_readable($okcdesign_theme_custom_theme_scss_file)) {
  file_put_contents($okcdesign_theme_custom_theme_scss_file, 'test');
}

/*
require "bower_components/scssphp/scss.inc.php";
// we have to recompile ALL scss file if user what to customize scss.
$default_theme_path = drupal_get_path('theme', variable_get('theme_default'));
$base_theme_path = drupal_get_path('theme', 'okcdesign');
$app_scss = "$base_theme_path/scss/app.scss";
// configure import pathes for our php scss compilator.
$import_paths = array(
  "$base_theme_path/bower_components/foundation/scss",
  "$base_theme_path/scss",
  $okcdesign_theme_custom_theme_scss_path,
);
$scss = new scssc();
$scss->setImportPaths($import_paths);
$scss_to_compile = file($app_scss);
//kpr($scss_to_compile);
$scss_to_compile = _add_custom_settings_import($scss_to_compile);
exit;

//$css_compiled = $scss->compile($scss_to_compile);
//file_put_contents($okcdesign_theme_custom_theme_scss_path . '/app.css', $css_compiled);

function _add_custom_settings_import($file) {
  foreach ($file as $line) {
    print trim($line) . "\n";
    if(strpos($line, "@import 'settings'") || strpos($line, '@import "settings"')) {
      print $line;
    }
  }
  return implode("\r\n", $file);
}
*/


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
