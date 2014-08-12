<?php

include_once 'drupalBootstrap.inc';
include_once '../theme_plugins_manager/theme_plugins_manager.php';

/**
 * Check if foundation_ui plugin work as expected.
 * We care especially about scss compilation with scssphp library, that
 * should never failed as this is a very important feature from OKC Design theme.
 */
class foundation_ui_Test extends PHPUnit_Framework_TestCase {

  // instance of foundation ui plugin.
  protected $foundation_ui = NULL;

  function setUp() {
    // load an instance of foundation_ui plugin.
    $this->foundation_ui = new foundation_ui();
  }

  /**
   * Check if we are able to build path to directory that will contains
   * all themes subdirectories, in drupal files.
   */
  function test_foundation_ui_get_okcdesign_custom_directory_path() {
    $path = foundation_ui_get_okcdesign_custom_directory_path();
    $this->assertContains(variable_get('file_public_path', conf_path() . '/files'), $path);
  }

  /**
   * Check that functions creating directories in drupal files work as expected.
   *
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
   * @depends test_foundation_ui_get_okcdesign_custom_directory_path
   */
  function test_foundation_ui_create_files_directories() {
    $theme_test_directory = 'phpunit_test_' . rand();
    $expected_path = foundation_ui_get_okcdesign_custom_directory_path() . '/' . $theme_test_directory;
    $result = foundation_ui_create_files_directories($theme_test_directory);
    $this->assertTrue($result);
    $this->assertFileExists($expected_path);
    rmdir($expected_path);
  }

  /**
   * @depends test_foundation_ui_create_files_directories
   * @depends test_foundation_ui_get_okcdesign_custom_directory_path
   */
  function test_foundation_ui_get_theme_scss_file_path() {
    $theme_test_directory = foundation_ui_get_okcdesign_custom_directory_path() . '/phpunit_test_' . rand();
    $path = foundation_ui_get_theme_scss_file_path($theme_test_directory);
    $this->assertContains(foundation_ui_get_okcdesign_custom_directory_path(), $path);
    rmdir($theme_test_directory);
  }

  /**
   * @depends test_foundation_ui_get_theme_scss_file_path
   */
  function test_foundation_ui_get_theme_custom_scss() {
    $this->assertInternalType('string', foundation_ui_get_theme_custom_scss());
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
    $this->assertFalse($form['expert']['#access']);

    // simulate an additionnal "expert" argument, that should make appear a new field
    // in theme plugin settings form.
    $_GET['q'] = 'admin/appearance/settings/okcdesign/expert';
    $form = $this->foundation_ui->settings_form();
    $this->assertTrue($form['expert']['#access']);

  }

  /**
   * Test function that parse a php array to a valid scss string.
   */
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

  /**
   * Check we are able to load scssphp compiler
   */
  function test_foundation_ui_get_scssphp_compiler() {
    $object = foundation_ui_get_scss_compiler();
    $this->assertInstanceof('scssc', $object);
  }

  /**
   * Check if we successfully add "@import user_settings" to our scss string we
   * want to send to scss php compiler.
   */
  function test_foundation_ui_rebuild_scss_import_file() {
    $app_scss_lines = explode("\n",'
      @import "foo";
      @import "settings";
      @import "foundation";
    ');
    $app_scss_string = foundation_ui_rebuild_scss_import_file($app_scss_lines);
    $needle = '@import "' . foudation_ui_get_custom_scss_filename() . '"';
    $this->assertContains($needle, $app_scss_string);
  }

  /**
   * Test scss compilation.
   * @depends test_foundation_ui_get_scssphp_compiler
   * @depends test_foundation_ui_rebuild_scss_import_file
   */
  function test_foundation_ui_compile_theme_scss() {
    $app_scss_lines = explode("\n",'
      @import "settings";
      @import "foundation/components/grid";
      .test{@include grid-column()}
    ');
    $app_scss_string = foundation_ui_rebuild_scss_import_file($app_scss_lines);
    $css = foundation_ui_compile_theme_scss('okcdesign', $app_scss_string);
    $this->assertContains('.column, .columns', $css);
  }

}
