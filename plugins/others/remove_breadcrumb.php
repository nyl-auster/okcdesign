<?php

class remove_breadcrumb {

  function hook_preprocess_page(&$variables) {
    drupal_set_breadcrumb(array());
  }

}

