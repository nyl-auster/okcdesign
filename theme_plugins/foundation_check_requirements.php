<?php

/**
 * Check if okcdesign is able to runs on current Drupal installation.
 * IF not, try to guess what's wrong and to inform user.
 */
class foundation_check_requirements extends theme_plugin {

  function hook_preprocess_page(&$variables) {

    // menu module is required.
    if (!module_exists('menu')) {
      drupal_set_message("menu module is required, please enable it.");
    }

    // jquery update in 1.10 or > is required.
    if (!module_exists('jquery_update')) {
      drupal_set_message(t('!module was not found. OKC Design requires jQuery 1.10 or higher. Please install !module, or Zurb Foundation plugins may not work correctly.',
        array(
          '!module' => l('jQuery Update', 'https://drupal.org/project/jquery_update', array('external' => TRUE, 'attributes' => array('target' => '_blank'))),
        )
      ), 'warning', FALSE);
      return;
    }

    // try to guess if user is using jquery update but with a wrong version.
    // unfortunately, this variable is not always set...
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