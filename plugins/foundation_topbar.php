<?php

class foundation_topbar {

  static function hook_theme(&$themes) {
    $themes['foundation_topbar'] = array(
      'variables' => array(
        'links_right' => array() ,
        'links_left' => array()
      ),
      'template' => 'templates/foundation_topbar',
    );
    $themes['foundation_topbar_submenu'] = array(
      'variables' => array('links' => array()),
      'template' => 'templates/foundation_topbar_submenu',
    );
  }

  static function hook_preprocess_foundation_topbar(&$variables) {

  }


}