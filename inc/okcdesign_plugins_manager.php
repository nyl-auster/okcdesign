<?php
/**
 * To let user choose how different parts of Drupal are affected
 * by foundation framework, we use a system of "plugin".
 *
 * Template.php implements all existing hooks and asked all plugins
 * if the have something to do in this hook. So a plugin can talk to
 * several hook.
 *
 * A plugin is simply a class, each method name MUST be exactly the name
 * of corresponding hook in template.php
 */

spl_autoload_register('autoload_theme_plugins');

// autoload classes from plugins folder
function autoload_theme_plugins($class_name) {
  $file = drupal_get_path('theme', 'okcdesign') . "/plugins/$class_name.php";
  if (is_readable($file)) {
    include_once $file;
  }
}

/**
 * Return registered plugins
 * @return mixed
 */
function _theme_get_plugins() {
  static $plugins = array();
  return $plugins ? $plugins : include drupal_get_path('theme', 'okcdesign') . "/theme_plugins.info.php";
}

/**
 * Invoke all plugins from a drupal hook. It will look for all
 * plugins responding to a hook and call them.
 *
 * For preprocess, variables are passed by reference so several plugins
 * may respond to one hook.
 * For theme functions, only one can be called as we need a return statement
 * to render html; so one a result is return, we return it and other plugins
 * are not called.
 */
function _invoke_theme_plugins($hook, &$arg1 = array(), &$arg2 = array(), &$arg3 = array(), &$arg4 = array()) {
  foreach (_theme_get_plugins()  as $class => $plugin_infos) {
    $plugin_hook = str_replace($GLOBALS['theme'] . '', 'hook', $hook);
    if (in_array($plugin_hook, $plugin_infos['hooks'])) {
      $result = $class::$plugin_hook($arg1, $arg2, $arg3, $arg4);
      // if we have a return, this is a theme function returning html,
      // we have to return it to Drupal.
      if ($result) return $result;
    }
  }
}
