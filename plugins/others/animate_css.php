<?php

class animate_css {

  function hook_html_head_alter(&$variables) {
    drupal_add_css(drupal_get_path('theme', OKCDESIGN_THEME_NAME) . '/' . OKCDESIGN_VENDORS_DIRECTORY . '/animate.css/animate.min.css');
  }

}

