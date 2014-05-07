<?php
/**
 * OKC Design theme use a system of plugin.
 * Thinks plugins as modules for a theme : only enable in theme settings administration
 * what you need.
 *
 * Template.php implements all needed hooks and then asked all plugins
 * if they have something to do inside this hook. So a plugin can talk to
 * several hooks.
 *
 * A plugin is simply a class, each method name MUST be exactly the name
 * of corresponding hook in template.php
 */
define('OKCDESGIN_THEME_PLUGINS_DIRECTORY', 'theme_plugins');
define('OKCDESGIN_THEME_PLUGINS_REGISTRY_FILE', 'theme_plugins_registry.php');

// we use an autoloader. Storing classes file path in theme info files
// would make things harder to maintain in case we change things...
// i hope also it would make life easier when porting code to
// Drupal 8, maybe using plugin API.
spl_autoload_register('okcdesign_plugins_autoloader');

/**
 * Autoloader for plugins.
 *
 * Look in theme_plugins directory for the requested class.
 * @param $class_name : the requested class name.
 */
function okcdesign_plugins_autoloader($class_name) {
  $file = drupal_get_path('theme', 'okcdesign') . "/" . OKCDESGIN_THEME_PLUGINS_DIRECTORY . "/$class_name.php";
  if (is_readable($file)) {
    include_once $file;
  }
}

/**
 * Helper to retrieve configuration of a plugin
 *
 * Each plugin configuration is saved into a variable inside theme settings
 * with the following naming convention :
 * theme_plugin_settings_{plugin_id}
 *
 * @param (string) $plugin_id : plugin machine name
 * @param (string) $name : name of setting to fetch
 * @param (mixed) $default_value : a default value if no value exists yet for this settings.
 * @return (mixed) value of the requested settings.
 */
function theme_plugin_get_setting($plugin_id, $name ,$default_value = NULL) {
  $settings = theme_get_setting("theme_plugin_settings_$plugin_id");
  $value =  isset($settings[$name]) ? $settings[$name] : $default_value;
  return $value;
}

/**
 * Return all registered plugins.
 * Plugins are registed in a theme_plugins_registry.php file
 * at the root of the theme.
 *
 * They were previously declared in theme info file, but that caused
 * troubles and fatal errors sometimes because of drupal cache.
 *
 * @return mixed
 */
function theme_get_plugins() {

  // static cache, this function my be call several times.
  static $plugins = array();
  if ($plugins) return $plugins;

  $plugins = include drupal_get_path('theme', 'okcdesign') .'/' . OKCDESGIN_THEME_PLUGINS_REGISTRY_FILE;

  // fetch plugins which required this plugin to work.
  // This will be used in administration UI only for now...
  foreach ($plugins as $id => $plugin) {
    $plugins[$id]['required_by_plugins'] = theme_get_required_by_plugins($id);
  }
  return $plugins;
}

/**
 * Tell us if a okcdesign plugin is enabled
 * A variable of the following form indicates us if a plugin is disabled or enabled :
 * theme_plugin_{plugin_id}
 *
 * @param $plugin : plugin id, as declared in plugins registry file.
 * @return bool
 */
function theme_plugin_is_enabled($plugin) {
  $result = theme_get_setting("theme_plugin_$plugin");
  return $result ? TRUE : FALSE;
}

/**
 * Get all enabled plugins.
 * @return array
 */
function theme_plugin_get_enabled_plugins() {
  $enabled = array();
  $plugins = theme_get_plugins();
  foreach($plugins as $id => $datas) {
    if (theme_get_setting("theme_plugin_$id")) {
      $enabled[$id] = $id;
    }
  }
  return $enabled;
}

/**
 * Invoke all plugins for a specific drupal hook. It will look for all
 * plugins responding to a hook and call them.
 *
 * - For preprocess, variables are passed by reference so several plugins
 * may respond to one hook.
 *
 * - For theme functions, only one plugin may respond, so if
 *   several plugins want to return a theme override, we return only the last...
 *
 * For now, it's up to user to not use plugins overriding the same drupal theme function
 */
function theme_plugins_invoke($hook, &$arg1 = array(), &$arg2 = array(), &$arg3 = array(), &$arg4 = array()) {

  $plugins = theme_get_plugins();
  $plugins_enabled = theme_plugin_get_enabled_plugins();
  $result = NULL;

  // plug in only enabled plugins.
  foreach ($plugins_enabled  as $plugin_id) {

    // get full plugin infos from info file.
    $plugin_infos = $plugins[$plugin_id];
    // plugin id is corresponding file / class name.

    // for okcdesign_preprocess_page, call method hook_preprocess_page() in plugin class.
    $method = str_replace('okcdesign' . '', 'hook', $hook);
    // if plugins declared a method to fire for this particular hook, call it.

    if (in_array($method, $plugin_infos['hooks'])) {
      $plugin = $plugin_id::get_instance();
      $result = $plugin->$method($arg1, $arg2, $arg3, $arg4);
    }
  }

  // if we have a return, a plugin implements a theme function returning html,
  // we have to return it to Drupal.
  if ($result) return $result;

}

/**
 * Return list of plugins than depends on a specific plugin.
 * So that in administration, user in informed that disable
 * a plugin may break other plugins.
 *
 * @param $plugin_id
 *   machine name of the plugin, as defined in theme info file.
 * @return array()
 *    array of plugin dependencies or empty array if no dependencies are found.
 */
function theme_get_required_by_plugins($plugin_id) {
  $plugins = theme_get_plugins();
  $required_by_plugins = array();
  foreach ($plugins as $id => $plugin) {
    if (isset($plugin['dependencies'])) {
      foreach ($plugin['dependencies'] as $dependency) {
        if ($dependency == $plugin_id) {
          $required_by_plugins[$id] = $plugins[$id];
        }
      }
    }
  }
  return $required_by_plugins;
}
