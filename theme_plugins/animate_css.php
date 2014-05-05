<?php

class animate_css extends theme_plugin_base {

  function hook_html_head_alter(&$variables) {
    drupal_add_css(drupal_get_path('theme', $this->base_theme_name) . '/' . $this->vendors_directory . '/animate.css/animate.min.css');
  }

}

