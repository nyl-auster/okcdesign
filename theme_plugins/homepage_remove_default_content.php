<?php

/**
 * Remove default content from homepage.
 *
 * By default, Drupal display a default content if no node
 * has been promoted to front page.
 */
class homepage_remove_default_content extends theme_plugin {

  function hook_preprocess_page(&$variables) {
    unset($variables['page']['content']['system_main']['default_message']);
  }

}