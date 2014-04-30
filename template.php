<?php
/**
 * @file
 *
 * Template.php
 *
 * All code is dispatched in plugins that may be enabled / disabled
 * in theme administration settings.
 * @see okcdesign_plugins_manager.php
 */

define('OKCDESIGN_THEME_NAME', 'okcdesign');
define('OKCDESIGN_VENDORS_DIRECTORY', 'bower_components');
define('OKCDESIGN_PLUGINS_DIRECTORY', 'plugins');

// load plugins system
include 'inc/okcdesign_plugins_manager.php';

/*=============================
   HOOKS
 ==============================*/

/**
 * Implements hook_theme()
 */
function okcdesign_theme() {
  $themes = array();
  okcdesign_plugins_dispatch(__FUNCTION__, $themes);
  return $themes;
}

/*=============================
   PREPROCESS
 ==============================*/

/**
 * Implements template_preprocess_page()
 */
function okcdesign_preprocess_page(&$variables) {
  okcdesign_plugins_dispatch(__FUNCTION__, $variables);
}

/**
 * Implements hook_css_alter()
 */
function okcdesign_css_alter(&$variables) {
  okcdesign_plugins_dispatch(__FUNCTION__, $variables);
}

/**
 * Implements hook_html_head_alter().
 */
function okcdesign_html_head_alter(&$variables) {
  okcdesign_plugins_dispatch(__FUNCTION__, $variables);
}

/**
 * Implements template_preprocess_foundation_topbar()
 */
function okcdesign_preprocess_foundation_topbar(&$variables) {
  okcdesign_plugins_dispatch(__FUNCTION__, $variables);
}


/*=============================
   THEME OVERRIDES
 ==============================*/

/**
 * Implements hook_theme_breadcrumb()
 */
function okcdesign_breadcrumb($variables) {
  $html = okcdesign_plugins_dispatch(__FUNCTION__, $variables);
  if ($html) return $html;
  return theme_breadcrumb($variables);
}

function okcdesign_status_messages($variables) {
  $html = okcdesign_plugins_dispatch(__FUNCTION__, $variables);
  if ($html) return $html;
  return theme_status_messages($variables);
}

