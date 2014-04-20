<?php

spl_autoload_register('autoload_theme_plugins');

// autoload classes from plugins folder
function autoload_theme_plugins($class_name) {
  $file = drupal_get_path('theme', 'okcfoundation_theme') . "/plugins/$class_name.php";
  if (is_readable($file)) {
    include_once $file;
  }
}

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

function _invoke_theme_plugins($hook, &$variables = array()) {
  foreach (_theme_get_plugins()  as $plugin) {
    $result = _invoke_theme_plugin_hook($plugin, $hook, $variables);
    if ($result) return $result;
  }
}

function _invoke_theme_plugin_hook($plugin, $hook, &$variables) {
  if (method_exists($plugin, $hook)) {
    $result = $plugin::$hook($variables);
    return $result;
  }
}