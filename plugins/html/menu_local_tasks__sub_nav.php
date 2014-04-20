<?php
/**
 * Class foundation_menu_top_bar
 *
 * Theme local tasks as foundation sub nav
 */

class menu_local_tasks__sub_nav {

  static function hook_menu_local_task($variables) {
    $link = $variables['element']['#link'];
    $link_text = $link['title'];
    if (!empty($variables['element']['#active'])) {
      // Add text to indicate active tab for non-visual users.
      $active = '<span class="element-invisible">' . t('(active tab)') . '</span>';

      // If the link does not contain HTML already, check_plain() it now.
      // After we set 'html'=TRUE the link will not be sanitized by l().
      if (empty($link['localized_options']['html'])) {
        $link['title'] = check_plain($link['title']);
      }
      $link['localized_options']['html'] = TRUE;
      $link_text = t('!local-task-title!active', array('!local-task-title' => $link['title'], '!active' => $active));
    }
    return '<dt' . (!empty($variables['element']['#active']) ? ' class="active"' : '') . '>' . l($link_text, $link['href'], $link['localized_options']) . "</dt>\n";
  }

  static function hook_menu_local_tasks($variables) {

    $output = '';
    if (!empty($variables['primary'])) {
      $variables['primary']['#prefix'] = '<h2 class="element-invisible">' . t('Primary tabs') . '</h2>';
      $variables['primary']['#prefix'] .= '<dl class="sub-nav">';
      $variables['primary']['#suffix'] = '</dl>';
      $output .= drupal_render($variables['primary']);
    }
    if (!empty($variables['secondary'])) {
      $variables['secondary']['#prefix'] = '<h2 class="element-invisible">' . t('Secondary tabs') . '</h2>';
      $variables['secondary']['#prefix'] .= '<dl class="tabs secondary">';
      $variables['secondary']['#suffix'] = '</dl>';
      $output .= drupal_render($variables['secondary']);
    }

    return $output;
  }

}