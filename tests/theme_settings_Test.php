<?php

include 'drupalBootstrap.inc';
require drupal_get_path('theme', 'okcdesign') . '/theme-settings.php';


/**
 * Test that plugins manager is working as expected
 * Class okcdesignPluginsManagerTest
 */
class theme_settings_Test extends PHPUnit_Framework_TestCase {

  /**
   * Expected plugins list
   * @return array
   */
  public function pluginsListDataProvider() {
    $plugins = theme_plugin_get_enabled_plugins();
    $datas = array();
    foreach ($plugins as $plugin_id => $plugin_datas) {
      $datas[] = array($plugin_id);
    }
    return $datas;
  }

  /**
   * Check that array is structured in a way to create a theme settings variable
   * as we expect it everywhere inside okc design theme.
   *
   * @dataProvider pluginsListDataProvider
   */
  function test_okcdesign_form_system_theme_settings_alter($plugin_id) {
    // let's see if all is allright, taking foundation_ui plugin as an example.
    $form = array();
    okcdesign_form_system_theme_settings_alter($form, array());
    $this->assertArrayHasKey($plugin_id, $form['okcdesign']);
    // print_r($form);
    $this->assertArrayHasKey("theme_plugin_$plugin_id", $form['okcdesign'][$plugin_id]);;
    $this->assertSame("checkbox", $form['okcdesign'][$plugin_id]["theme_plugin_$plugin_id"]['#type']);

    // check our configuration form is here with the right keys.
    $plugin = new $plugin_id;
    if (method_exists($plugin, 'settings_form') && $plugin->settings_form()) {
      $this->assertArrayHasKey("theme_plugin_settings_$plugin_id", $form['okcdesign'][$plugin_id]["settings_$plugin_id"]);
      $this->assertTrue($form['okcdesign'][$plugin_id]["settings_$plugin_id"]["theme_plugin_settings_$plugin_id"]['#tree']);
    }
  }

}
