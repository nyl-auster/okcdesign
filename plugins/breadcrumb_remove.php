<?php

class breadcrumb_remove {

  function hook_preprocess_page(&$variables) {
    drupal_set_breadcrumb(array());
  }

}

