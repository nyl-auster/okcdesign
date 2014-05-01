<?php

class homepage_remove_title {

  function hook_preprocess_page(&$variables) {
    if (drupal_is_front_page()) {
      drupal_set_title('');
    }
  }

}