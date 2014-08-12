<?php

require_once 'drupalBootstrap.inc';

/**
 * Test foundation theme plugin
 * Class foundationTest
 */
class foundation_Test extends PHPUnit_Framework_TestCase {

  function setUp() {
    // simulate that drupal is serving a webpage. False indicates that we don't actually print html.
    // this is required to gather all drupal css & js files.
    $page = menu_execute_active_handler('node', FALSE);
    $this->html_page = drupal_render_page($page);
  }

  function test_foundation_css_injection() {
    // check if foundation css file is included in css drupal list :
    // we look for a {themename}/css/app.css ( included by foundation plugin)
    // or a files/okcdesign/{themename}/user_app.css included by foundation_ui plugin.
    $styles = drupal_add_css();
    $foundation_css = drupal_get_path('theme', variable_get('theme_default')) . '/css/app.css';
    // if foundation_ui is enabled && its generated file is readable, we load user_app.css instead.
    if (theme_plugin_is_enabled('foundation_ui')) {
      $user_foundation_css = variable_get('file_public_path', conf_path() . '/files') . '/okcdesign/' . variable_get('theme_default', 'okcdesign') . '/user_app.css';
      if (is_readable($user_foundation_css)) {
        $foundation_css = $user_foundation_css;
      }
    }
    $this->assertArrayHasKey($foundation_css, $styles, "foundation app.css not injected in Drupal ! ");
  }

  /**
   * Check if required css and js file seems to be correctly injected into Drupal
   */
  function test_foundation_js_injection() {
    $all_js = drupal_add_js();
    $foundation_js = drupal_get_path('theme', 'okcdesign') . '/bower_components/foundation/js/foundation.min.js';
    $this->assertArrayHasKey($foundation_js, $all_js, "foundation.min.js not included.");

    $modernizr_js = drupal_get_path('theme', 'okcdesign') . '/bower_components/modernizr/modernizr.js';
    $this->assertArrayHasKey($modernizr_js, $all_js, "modernizr.js not injected in Drupal.");
  }

  /**
   * Check if required css and js file seems to be correctly injected into Drupal
   */
  function test_foundation_viewport() {
    $headers = drupal_get_html_head();
    $this->assertContains('<meta name="viewport" content="width=device-width, initial-scale=1.0" />', $headers, "Incorrect viewport in html header");
  }

  function test_foundation_utf8_charset() {
    $headers = drupal_get_html_head();
    $this->assertContains('<meta charset="utf-8" />', $headers, "utf-8 charset not found");
  }

}