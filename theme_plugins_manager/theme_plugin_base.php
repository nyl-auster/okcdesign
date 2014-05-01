<?php
/**
 * Base class to build a plugin, provided helpers variables.
 */

class theme_plugin_base {

  protected $base_theme_name = OKCDESIGN_THEME_NAME;
  protected $base_theme_path = '';
  protected $default_theme_path = '';
  protected $vendors_directory = OKCDESIGN_VENDORS_DIRECTORY;

  function __construct() {
    $this->base_theme_path = drupal_get_path('theme', OKCDESIGN_THEME_NAME);
    $this->default_theme_path = drupal_get_path('theme', $GLOBALS['theme']);
  }

}

