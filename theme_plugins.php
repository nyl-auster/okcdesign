<?php

/**
 * Return plugins defined in info file.
 * @return mixed
 */
function _theme_get_plugins() {
  static $plugins = array();
  if ($plugins) return $plugins;
  $themes = list_themes();
  $theme = $themes[$GLOBALS['theme']];
  if (isset($theme->info['theme_plugins'])) {
    $plugins = $theme->info['theme_plugins'];
  }
  return $plugins;
}

function _invoke_theme_plugins($hook, &$args = array()) {
  foreach (_theme_get_plugins()  as $plugin => $path) {
    $file = drupal_get_path('theme', 'okcfoundation_theme') . "/$path";
    require_once $file;
    _invoke_theme_plugin_hook($plugin, 'hook_css_alter', $args);
  }
}

function _invoke_theme_plugin_hook($plugin, $hook, &$args) {
  if (method_exists($plugin, $hook)) {
    return call_user_func_array("$plugin::$hook", $args);
  }
}