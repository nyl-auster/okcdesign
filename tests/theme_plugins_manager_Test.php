<?php

include 'drupalBootstrap.inc';
include '../theme_plugins_manager/theme_plugins_manager.php';

/**
 * Test that plugins manager is working as expected
 * Class okcdesignPluginsManagerTest
 */
class theme_plugins_manager_Test extends PHPUnit_Framework_TestCase {

  protected $plugins_registry_file;

  function setUp() {
    $this->plugins_registry_file = drupal_get_path('theme', 'okcdesign') . '/' . OKCDESGIN_THEME_PLUGINS_REGISTRY_FILE;
  }

  /**
   * Expected plugins list
   * @return array
   */
  public function pluginsListDataProvider() {
    return array(
      array('foundation'),
      array('foundation_ui'),
      array('foundation_topbar'),
      array('foundation_check_requirements'),
      array('foundation_breadcrumb'),
      array('foundation_grid_viewer'),
      array('dynamic_sidebars'),
      array('foundation_alert_box'),
    );
  }

  /**
   * List of required plugins for OKC Design theme to work.
   */
  public function requiredPlugins() {
    return array(
      'foundation',
      'foundation_check_requirements',
      'dynamic_sidebars',
    );
  }

  /**
   * Check if plugins registry file actually exists.
   */
  function test_plugin_registry_file_exists() {
    $this->assertFileExists($this->plugins_registry_file);
  }

  /**
   * Check if plugins registry file is returning an array as needed.
   */
  function test_plugin_registry_file_returns_array() {
    $plugins = include $this->plugins_registry_file;
    $this->assertInternalType('array', $plugins);
  }

  /**
   * Test that registry file return an array containing expected plugins.
   * @dataProvider pluginsListDataProvider
   */
  function test_plugin_registry_file_array_content($plugin) {
    $plugins = include $this->plugins_registry_file;
    $this->assertArrayHasKey($plugin, $plugins);
  }

  /**
   * Check if theme_get_plugins is returning an array.
   * Check if we find a "foundation" key which is the main plugin for our theme.
   */
  function test_theme_get_plugins_return_array() {
    $plugins = theme_get_plugins();
    $this->assertInternalType('array', $plugins);
    return $plugins;
  }

  /**
   * Check if theme_get_plugins is returning an array.
   * Check if we find a "foundation" key which is the main plugin for our theme.
   * @dataProvider pluginsListDataProvider
   */
  function test_theme_get_plugins_return_array_content($plugin) {
    $plugins = theme_get_plugins();
    $this->assertArrayHasKey($plugin, $plugins);
    return $plugins;
  }

  /**
   * Try to load foundation plugins
   */
  function test_theme_plugin_autoloader() {
    $plugin = foundation::get_instance();
    $this->assertInstanceOf('foundation', $plugin);
    $plugin = foundation_topbar::get_instance();
    $this->assertInstanceOf('foundation_topbar', $plugin);
  }

  function test_theme_plugin_is_enabled() {

    $plugin_id = 'foundation';

    // Always do our test on okcdesign rather than default active theme.
    // this if for theme_get_setting function
    $GLOBALS['theme_key'] = 'okcdesign';

    // disable foundation plugin
    $theme_settings = variable_get("theme_okcdesign_settings");
    $theme_settings["theme_plugin_$plugin_id"] = 0;
    variable_set("theme_okcdesign_settings", $theme_settings);
    drupal_static_reset('theme_get_setting');

    $this->assertFalse(theme_plugin_is_enabled($plugin_id));

    // re-enable foundation plugin
    $theme_settings = variable_get("theme_okcdesign_settings");
    $theme_settings["theme_plugin_$plugin_id"] = 1;
    variable_set("theme_okcdesign_settings", $theme_settings);
    drupal_static_reset('theme_get_setting');
    $this->assertTrue(theme_plugin_is_enabled($plugin_id));

  }

  /**
   * Make sure theme_plugin_get_settings() works as expected.
   */
  function test_theme_plugin_get_setting() {

    // Always do our test on okcdesign rather than default active theme.
    // this if for theme_get_setting function
    $GLOBALS['theme_key'] = 'okcdesign';

    $plugin_id = 'phpunit_test';

    // theme_plugin_get_settings shloud return NULL if setting does not exist.
    $result = theme_plugin_get_setting($plugin_id, 'test');
    $this->assertNull($result);

    // function shloud return default value when settings does not exist.
    $result = theme_plugin_get_setting($plugin_id, 'test', 'default_value');
    $this->assertSame($result, 'default_value');

    $theme_settings = variable_get("theme_okcdesign_settings");
    $theme_settings["theme_plugin_settings_$plugin_id"] = array('test' => 'value');
    variable_set("theme_okcdesign_settings", $theme_settings);
    drupal_static_reset('theme_get_setting');

    // function shloud now return "value", fetching from theme settings.
    $result = theme_plugin_get_setting($plugin_id, 'test', 'default_value');
    $this->assertSame($result, 'value');

    // clean settings, remove our test variable.
    $theme_settings = variable_get("theme_okcdesign_settings");
    unset($theme_settings["theme_plugin_settings_$plugin_id"]);
    variable_set("theme_okcdesign_settings", $theme_settings);
    drupal_static_reset('theme_get_setting');

    // theme_plugin_get_settings shloud return NULL because setting does not exist anymore.
    $result = theme_plugin_get_setting($plugin_id, 'test');
    $this->assertNull($result);

  }

  function test_theme_plugin_get_enabled_plugins() {

    // Always do our test on okcdesign rather than default active theme.
    // this if for theme_get_setting function
    $GLOBALS['theme_key'] = 'okcdesign';

    $plugins = theme_get_plugins();
    // disable all plugins
    $theme_settings = variable_get("theme_okcdesign_settings");
    foreach ($plugins as $plugin_id => $datas) {
      $theme_settings["theme_plugin_$plugin_id"] = 0;
    }
    variable_set("theme_okcdesign_settings", $theme_settings);
    drupal_static_reset('theme_get_setting');

    // each plugin should be disabled now...
    foreach ($plugins as $plugin_id => $datas) {
      $this->assertFalse(theme_plugin_is_enabled($plugin_id));
    }

    $this->assertEmpty(theme_plugin_get_enabled_plugins());

    // re-enable only required plugins :
    $theme_settings = variable_get("theme_okcdesign_settings");
    $required_plugins = $this->requiredPlugins();

    foreach($required_plugins as $plugin_id) {
      $theme_settings["theme_plugin_$plugin_id"] = 1;
    }
    variable_set("theme_okcdesign_settings", $theme_settings);
    drupal_static_reset('theme_get_setting');

    // each required plugin should be enabled now.
    foreach ($required_plugins as $plugin_id) {
      $this->assertTrue(theme_plugin_is_enabled($plugin_id));
    }

    // theme_get_enabled_plugins must return exactly the same plugins :
    $this->assertSame(ksort($required_plugins), ksort(array_keys(theme_plugin_get_enabled_plugins())));

  }

}
