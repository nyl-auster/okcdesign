<?php

include 'drupalBootstrap.inc';

/**
 * Check global required things for okcdesign to work as expected
 */
class okcdesignTest extends PHPUnit_Framework_TestCase {

  /**
   * Check that required files and directories are here
   */
  function test_app_css_exists() {
    $this->assertFileExists(drupal_get_path('theme', 'okcdesign') . '/css/app.css');
    $this->assertFileExists(drupal_get_path('theme', 'okcdesign') . '/scss/app.scss');
    $this->assertFileExists(drupal_get_path('theme', 'okcdesign') . '/scss/_settings.scss');
    $this->assertFileExists(drupal_get_path('theme', 'okcdesign') . '/js/app.js');
    $this->assertFileExists(drupal_get_path('theme', 'okcdesign') . '/templates/page.tpl.php');
    $this->assertFileExists(drupal_get_path('theme', 'okcdesign') . '/templates/page.tpl.php');
    $this->assertFileExists(drupal_get_path('theme', 'okcdesign') . '/bower_components/foundation/scss/foundation/components/_global.scss');
  }



}