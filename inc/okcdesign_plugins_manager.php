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

spl_autoload_register('okcdesign_plugins_autoloader');

// autoload classes from plugins folder
// simply include the $class_name file is not sufficient because
// we might need to include a parent class to, and we dont know its name.
function okcdesign_plugins_autoloader($class_name) {
  $file = drupal_get_path('theme', 'okcdesign') . "/plugins/$class_name.php";
  if (is_readable($file)) {
    include_once $file;
  }
}

/**
 * Return registered plugins
 * @return mixed
 */
function okcdesign_get_plugins() {

  static $plugins = array();
  if ($plugins) return $plugins;

  $themes = list_themes();
  $theme = $themes['okcdesign'];
  if (isset($theme->info['okcdesign_plugins'])) {
    $plugins = $theme->info['okcdesign_plugins'];
  }
  return $plugins;
}

/**
 * Invoke all plugins for a specific drupal hook. It will look for all
 * plugins responding to a hook and call them.
 *
 * For preprocess, variables are passed by reference so several plugins
 * may respond to one hook.
 * For theme functions, only one can be called as we need a return statement
 * to render html; so one a result is return, we return it and other plugins
 * are not called.
 */
function okcdesign_invoke_plugins($hook, &$arg1 = array(), &$arg2 = array(), &$arg3 = array(), &$arg4 = array()) {

  $plugins = okcdesign_get_plugins();
  $plugins_enabled = array_filter(theme_get_setting('okcdesign_plugins_enabled'));
  
  // plug in only enabled plugins.
  foreach ($plugins_enabled  as $plugin_id) {
    // get full plugin infos from info file.
    $plugin_infos = $plugins[$plugin_id];
    // plugin id is corresponding file / class name.
    $class = $plugin_id;
    // for okcdesign_preprocess_page, call method hook_preprocess_page() in plugin class.
    $method = str_replace('okcdesign' . '', 'hook', $hook);
    // if plugins declared a method to fire for this particular hook, call it.
    if (in_array($method, $plugin_infos['hooks'])) {
      $result = $class::$method($arg1, $arg2, $arg3, $arg4);
      // if we have a return, this is a theme function returning html,
      // we have to return it to Drupal.
      if ($result) return $result;
    }
  }

}
