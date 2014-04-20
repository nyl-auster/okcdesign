<?php
/**
 * Class foundation_menu_top_bar
 *
 * Theme main menu as foundation nav bar
 */

class system_main_menu__buttons_group {

  static function hook_links__system_main_menu($variables) {
     $theme = new theme_links_helper();
     $theme->global_wrapper_markup = 'div';
     $theme->global_wrapper_attributes = array('class' => array('nav-bar'));
     $theme->list_wrapper_markup = 'ul';
     $theme->list_wrapper_attributes = array('class' => array('button-group'));
     $theme->list_markup = 'li';
     $theme->link_attributes = array('class' => array('button'));
     return $theme->render($variables);
  }

}