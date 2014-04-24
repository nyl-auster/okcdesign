<?php

class animate_css {

  function hook_html_head_alter(&$variables) {
    drupal_add_css(drupal_get_path('theme', 'okcdesign') . '/vendors/daniel_eden/animate.css/animate.min.css');
  }

}

