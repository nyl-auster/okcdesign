<?php
/**
 * @file theme_plugins_manager.php
 *
 * Plugin system for themes. Think plugins as module for a theme.
 * Plugins may be enabled / disabled in theme admin settings.
 *
 * Template.php implements all needed hooks and then asked all plugins
 * if they have something to do inside this hook.
 *
 * A plugin is simply a class, each method name MUST be exactly the name
 * of corresponding hook in template.php
 *
 * Only okcdesign base theme is able to create plugins for now.
 */

// plugins directory
define('OKC_THEME_PLUGINS_DIRECTORY', 'theme_plugins');
// file to declare plugins to theme. This is not done anymore in theme
// info file because of caching issues.
define('OKC_THEME_PLUGINS_REGISTRY_FILE', 'theme_plugins_registry.php');

// We use an autoloader to load plugins classes.
spl_autoload_register('theme_plugins_autoloader');

/**
 * Autoloader for plugins.
 *
 * Look in theme_plugins directory for the requested class.
 * @param $class_name : the requested class name.
 * @return string filename is a file is found, otherwise NULL
 */
function theme_plugins_autoloader($class_name) {
  $file = drupal_get_path('theme', 'okcdesign') . "/" . OKC_THEME_PLUGINS_DIRECTORY . "/$class_name.php";
  if (is_readable($file)) {
    include_once $file;
    return $file;
  }
}

/**
 * Helper to retrieve configuration of a plugin
 *
 * Each plugin configuration is saved into a variable inside current theme settings variable
 * with the following naming convention : theme_plugin_settings_{plugin_id}
 *
 * @param string $plugin_id : plugin machine name
 * @param string $name : name of setting to fetch
 * @param mixed $default_value : a default value if no value exists yet for this settings.
 * @return string|null : value of the requested setting, null if settings does not exists
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

  $plugins = include drupal_get_path('theme', 'okcdesign') .'/' . OKC_THEME_PLUGINS_REGISTRY_FILE;

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
    if (theme_plugin_is_enabled($id)) {
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
  $html = NULL;

  // static cache for plugin instances, we make sure each plugin is
  // instanciated only one time per http request inside this function.
  static $factory = array();

  // call only enabled plugins.
  foreach ($plugins_enabled  as $plugin_id) {

    // get full plugin infos from info file.
    $plugin_infos = $plugins[$plugin_id];
    // plugin id is corresponding file / class name.

    // for okcdesign_preprocess_page, call method hook_preprocess_page() in plugin class.
    $method = str_replace('okcdesign' . '', 'hook', $hook);

    // if plugins declared a method to fire for this particular hook, call it.
    if (isset($plugin_infos['hooks']) && in_array($method, $plugin_infos['hooks'])) {

      // put plugin object in the factory if not already in.
      if (empty($factory[$plugin_id])) $factory[$plugin_id] = new $plugin_id();

      // Let our phpunit tests know if called method has not been found.
      $html = $factory[$plugin_id]->$method($arg1, $arg2, $arg3, $arg4);

    }
  }

  // this should contain html when this function is called from a theme hook function.
  return $html;
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
