<?php

include 'drupalBootstrap.inc';
include 'theme_plugins_manager/theme_plugins_manager.php';

/**
 * Check if foundation_ui plugin work as expected.
 * We care especially about scss compilation with scssphp library, that
 * should never failed as this is a very important feature from OKC Design theme.
 */
class okcdesignPluginFoundationUiTest extends PHPUnit_Framework_TestCase {

  // instance of foundation ui plugin.
  protected $foundation_ui = NULL;

  function __construct() {
    // load an instance of foundatio_ui plugin.
    $this->foundation_ui = foundation_ui::get_instance();
  }

  /**
   * Check we are able to load scssphp compiler
   */
  function test_foundation_ui_get_scssphp_compiler() {
    $object = foundation_ui_get_scssphp_compiler();
    $this->assertInstanceof('scssc', $object);
  }

  /**
   * Check settings form is correctly build.
   */
  function test_form() {

    $form = $this->foundation_ui->settings_form();
    $this->assertInternalType('array', $form);
    $this->assertArrayHasKey('primary-color', $form);
    $this->assertArrayHasKey('secondary-color', $form);
    $this->assertArrayHasKey('total-columns', $form);
    $this->assertArrayHasKey('row-width', $form);
    $this->assertArrayHasKey('header-font-color', $form);
    $this->assertArrayNotHasKey('expert', $form);

    // simulate an additionnal "expert" argument, that should make appear a new field
    // in theme plugin settings form.
    $_GET['q'] = 'admin/appearance/settings/okcdesign/expert';
    $form = $this->foundation_ui->settings_form();
    $this->assertArrayHasKey('expert', $form);

  }

  /**
   * If this test fails, double check recursively that permission are correctly set
   * for files directory. Group should at least be _www so that phpunit is able to create
   * directories.
   *
   * This is an example of expected directory structure :
   *
   * files
   *   okcdesign
   *     mytheme
   *       theme_custom_settings.scss
   *       app.css
   */
  function test_foundation_ui_set_theme_custom_scss_directory() {
    $theme_test_directory = 'phpunit_test_' . rand();
    $expected_path = foundation_ui_get_okcdesign_custom_directory_path() . '/' . $theme_test_directory;
    $result = foundation_ui_create_files_directories($theme_test_directory);
    $this->assertTrue($result);
    $this->assertFileExists($expected_path);
    // clean directory
    rmdir($expected_path);
  }

  function test_foundation_ui_creating_scss_string_from_array() {
    $values = array(
      'primary-color' => 'blue',
      'secondary-color' => '#333',
      'row-width' => 'rem-calc(1000)',
    );
    $scss_string = foundation_ui_create_scss_string_from_array($values);
    $this->assertInternalType('string', $scss_string);
    $this->assertContains('$primary-color', $scss_string);
    $this->assertContains('$secondary-color', $scss_string);
    $this->assertContains('$row-width', $scss_string);
  }

}
