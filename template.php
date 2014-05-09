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

function okcdesign_system_theme() {
  if(drupal_is_front_page()) {
    return 'okcdesign_layout_homepage';
  }
}

/**
 * Implements hook_okcgrid();
 */
function okcdesgin_okcgrid() {
  $grid['default'] = array(
    'system-main' => 'large-6 columns',
    'user-online' => 'large-6 small-12 columns',
    '#context callback' => 'drupal_match_path',
    '#context arguments' => array($_GET['q'], '<front>'),
  );
  return $grid;
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

