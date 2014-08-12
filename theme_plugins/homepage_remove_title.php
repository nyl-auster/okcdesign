<?php

/**
 * Remove node title from homepage.
 */
class homepage_remove_title extends theme_plugin {

  function hook_preprocess_page(&$variables) {
    if (drupal_is_front_page()) {
      drupal_set_title('');
    }
  }

}