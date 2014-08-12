<?php
/**
 * Override of theme_breadcrumb()
 *
 * Theme drupal breadcrumb as a foundation breadcrumb
 */

class foundation_breadcrumb extends theme_plugin {

  function hook_breadcrumb($variables) {

    if (empty($variables['breadcrumb'])) return;

    // Provide a navigational heading to give context for breadcrumb links to
    // screen-reader users. Make the heading invisible with .element-invisible.
    $html = '<h2 class="element-invisible">' . t('You are here') . '</h2>';
    $html .= '<ul class="breadcrumbs">';
    foreach ($variables['breadcrumb'] as $key => $value) {
      $html .= '<li>' . $value . '</li>';
    }
    $html .= '<li class="current"><a href="#">' . drupal_get_title() . '</a></li>';
    $html .= '</ul>';
    return $html;

  }

}