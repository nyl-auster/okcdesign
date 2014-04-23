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

    foreach (element_children($variables['links_left']) as $i) {
      // php 5.4 will complain about references without this extra affectation...
      self::add_active_class($link = &$variables['links_left'][$i]);
    }

    foreach (element_children($variables['links_right']) as $i) {
      self::add_active_class($link = &$variables['links_right'][$i]);
    }
  }

  /**
   * Add missing active class, that is normally handled by theme_links
   * @param $link
   */
  static function add_active_class(&$link) {
    global $language_url;
    global $user;
    if (isset($link['#href'])
      && ($link['#href'] == $_GET['q'] || ($link['#href'] == '<front>' && drupal_is_front_page()))
      && (empty($link['language']) || $link['language']->language == $language_url->language)
      // hack for user page
      || $_GET['q'] == 'user/' . $user->uid && $link['#href'] == 'user') {
        // victory !
        $link['#attributes']['class'][] = 'active';
    }
  }


}