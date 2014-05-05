<?php
/**
 * Base class to build a plugin, provided helpers variables.
 */

class theme_plugin_base {

  protected $base_theme_name = '';
  // path of okcdesign theme
  protected $base_theme_path = '';
  // default theme for the site
  protected $default_theme_path = '';
  protected $vendors_directory = '';

  function __construct() {
    $this->base_theme_name = theme_get_setting('okcdesign_theme_name');
    $this->base_theme_path = drupal_get_path('theme', $this->base_theme_name);
    $this->vendors_directory = theme_get_setting('okcdesign_vendors_directory');
    $this->default_theme_path = drupal_get_path('theme', $GLOBALS['theme']);
  }

}

