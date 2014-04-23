<?php

class homepage_remove_title {

  static function hook_preprocess_page(&$variables) {
    drupal_set_title('');
  }

}