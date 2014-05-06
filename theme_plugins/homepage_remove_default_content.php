<?php

class homepage_remove_default_content extends theme_plugin {

  function hook_preprocess_page(&$variables) {
    unset($variables['page']['content']['system_main']['default_message']);
  }

}