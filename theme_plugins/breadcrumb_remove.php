<?php

class breadcrumb_remove extends theme_plugin_base {

  function hook_preprocess_page(&$variables) {
    drupal_set_breadcrumb(array());
  }

}

