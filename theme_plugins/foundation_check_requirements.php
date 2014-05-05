<?php

class foundation_check_requirements {

  function hook_preprocess_page(&$variables) {

    // inform user that jquery_update is required for our theme
    if (!module_exists('jquery_update')) {
      drupal_set_message(t('!module was not found. OKC Design requires jQuery 1.10 or higher. Please install !module, or Zurb Foundation plugins may not work correctly.',
        array(
          '!module' => l('jQuery Update', 'https://drupal.org/project/jquery_update', array('external' => TRUE, 'attributes' => array('target' => '_blank'))),
        )
      ), 'warning', FALSE);
      return;
    }

    // inform user if we guess that is choose a wrong version of jquery in jquery_update
    // configuration form.
    $jquery_version = variable_get('jquery_update_jquery_version', '1.10');
    if (!version_compare($jquery_version, '1.10', '>=')) {
      drupal_set_message(t('Version of jQuery does not meet the minimum version requirement. OKC design and foundation requires jQuery 1.10 or higher.',
        array(
          '!module' => l('jQuery Update', 'https://drupal.org/project/jquery_update', array('external' => TRUE, 'attributes' => array('target' => '_blank'))),
        )
      ), 'warning', FALSE);
    }

  }

}