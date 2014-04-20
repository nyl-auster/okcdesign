<?php
/**
 * Class foundation_breadcrumb
 *
 * Theme breadcrumb as a foundation breadcrumb
 */

class foundation_breadcrumb {

   static function okcfoundation_theme_theme_breadcrumb($variables) {
     $breadcrumb = $variables['breadcrumb'];

     if (!empty($breadcrumb)) {
       // Provide a navigational heading to give context for breadcrumb links to
       // screen-reader users. Make the heading invisible with .element-invisible.
       $breadcrumbs = '<h2 class="element-invisible">' . t('You are here') . '</h2>';

       $breadcrumbs .= '<ul class="breadcrumbs">';

       foreach ($breadcrumb as $key => $value) {
         $breadcrumbs .= '<li>' . $value . '</li>';
       }

       $title = strip_tags(drupal_get_title());
       $breadcrumbs .= '<li class="current"><a href="#">' . $title. '</a></li>';
       $breadcrumbs .= '</ul>';

       return $breadcrumbs;
     }
   }

}