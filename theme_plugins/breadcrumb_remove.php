<?php

/**
 * Simply remove breadcrumb from all pages...
 */
class breadcrumb_remove extends theme_plugin {

  function hook_preprocess_page(&$variables) {
    drupal_set_breadcrumb(array());
  }

}

