<?php

/**
 * Test foundation theme plugin
 * Class foundationTest
 */
class foundationTest extends PHPUnit_Framework_TestCase {

  /**
   * Check if required css and js file seems to be correctly injected into Drupal
   */
  function test_foundation_is_correctly_injected() {

   // simulate that drupal is serving a webpage. False indicates that we don't actually print html.
    // this is required to gather all drupal css & js files.
    $page = menu_execute_active_handler('node', FALSE);
    drupal_render_page($page);

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

    $this->assertArrayHasKey($foundation_css, $styles);

    $all_js = drupal_add_js();
    $foundation_js = drupal_get_path('theme', 'okcdesign') . '/bower_components/foundation/js/foundation.min.js';
    $this->assertArrayHasKey($foundation_js, $all_js);

    $modernizr_js = drupal_get_path('theme', 'okcdesign') . '/bower_components/modernizr/modernizr.js';
    $this->assertArrayHasKey($modernizr_js, $all_js);
  }

}