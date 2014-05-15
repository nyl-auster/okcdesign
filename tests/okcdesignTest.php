<?php
/**
 * Before every release :
 * - Run all phpunits test
 * - Complete with following manual tests :
 *
 * - lauch drush command "drush okc-theme", enable theme and see if all is ok.
 * - go to admin settings of current active theme, check and unchecked plugin,
 *   see it it does what it is supposed to.
 * - launch grunt in subtheme, see if scss is correctly compiled to css.
 */

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
    $this->assertFileExists(drupal_get_path('theme', 'okcdesign') . '/templates/foundation_topbar.tpl.php');
    $this->assertFileExists(drupal_get_path('theme', 'okcdesign') . '/templates/foundation_topbar_submenu.tpl.php');
    $this->assertFileExists(drupal_get_path('theme', 'okcdesign') . '/bower_components/foundation/scss/foundation/components/_global.scss');
    $this->assertFileExists(drupal_get_path('theme', 'okcdesign') . '/theme_plugins');
    $this->assertFileExists(drupal_get_path('theme', 'okcdesign') . '/theme_plugins_manager/theme_plugins_manager.php');
    $this->assertFileExists(drupal_get_path('theme', 'okcdesign') . '/okcdesign.drush.inc');
  }



}