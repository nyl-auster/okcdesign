<?php
/**
 * Class foundation_menu_top_bar
 *
 * Set a Drupal menu to a foundation top bar
 * http://foundation.zurb.com/docs/components/topbar.html
 */

class system_main_menu_as_nav_bar {

  static function okcfoundation_theme_links__system_main_menu($variables) {
     $theme = new theme_links_api();
     $theme->global_wrapper_markup = 'div';
     $theme->global_wrapper_attributes = array('class' => array('nav-bar'));
     $theme->list_wrapper_markup = 'ul';
     $theme->list_wrapper_attributes = array('class' => array('button-group'));
     $theme->list_markup = 'li';
     $theme->link_attributes = array('class' => array('button'));
     return $theme->render($variables);
  }

}