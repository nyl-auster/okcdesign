<?php

include 'drupalBootstrap.inc';


/**
 * Test that plugins manager is working as expeted
 * Class okcdesignPluginsManagerTest
 */
class theme_plugins_manager_Test extends PHPUnit_Framework_TestCase {

  function setUp() {
    include 'drupalBootsrap.inc';
    include 'theme_plugins_manager/theme_plugins_manager.php';
  }

  /**
   * Make sur our constants are correclty defined
   */
  function test_constants() {
    $this->assertTrue(defined("OKCDESGIN_THEME_PLUGINS_DIRECTORY"), 'Constant OKCDESGIN_THEME_PLUGINSS_DIRECTORY is undefined');
    $this->assertTrue(defined("OKCDESGIN_THEME_PLUGINS_REGISTRY_FILE"), 'Constant OKCDESGIN_THEME_PLUGINS_REGISTRY_FILE is undefined');
  }

  /**
   * Check if theme_get_plugins is returning an array.
   * Check if we find a "foundation" key which is the main plugin for our theme.
   */
  function test_plugin_registry_file() {
    $plugin_file = drupal_get_path('theme', 'okcdesign') . '/okcdesign.info.plugins.php';
    $this->assertFileExists($plugin_file);
  }

  /**
   * Check if theme_get_plugins is returning an array.
   * Check if we find a "foundation" key which is the main plugin for our theme.
   */
  function test_theme_get_plugins() {
    $plugins = theme_get_plugins();
    $this->assertInternalType('array', $plugins);
    return $plugins;
  }

  /**
   * Try to load foundation plugin and see if it works as expecter
   * @depends test_theme_get_plugins
   */
  function test_theme_plugin_autoloader($plugins) {
    $this->assertInternalType('array', $plugins);
    $this->assertArrayHasKey('foundation', $plugins);
    $plugin = foundation::get_instance();
    $this->assertInstanceOf('foundation', $plugin);
  }

  /**
   * Check foundation plugin is enabled in current theme.
   */
  function test_foundation_plugin_is_enabled() {
    $enabled = theme_get_setting('theme_plugin_foundation');
    $this->assertEquals(1, $enabled);
  }

  /**
   * Check if required css and js file seems to be correctly injected into Drupal
   */
  function test_foundation_is_correctly_included() {
    // simulate that drupal is servring a webpage. False indicates that we don't actually print html.
    // this is required to gather all drupal css & js files.
    $page = menu_execute_active_handler('node', FALSE);
    $html = drupal_render_page($page);

    // check if foundation css file is included in css drupal list :
    $styles = drupal_add_css();
    $foundation_css = drupal_get_path('theme', variable_get('theme_default')) . '/css/app.css';
    $this->assertArrayHasKey($foundation_css, $styles);

    $all_js = drupal_add_js();
    $foundation_js = drupal_get_path('theme', 'okcdesign') . '/bower_components/foundation/js/foundation.min.js';
    $this->assertArrayHasKey($foundation_js, $all_js);

    $modernizr_js = drupal_get_path('theme', 'okcdesign') . '/bower_components/modernizr/modernizr.js';
    $this->assertArrayHasKey($modernizr_js, $all_js);
  }

}
