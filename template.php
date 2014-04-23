<?php
/**
 * @file
 *
 * Template.php
 */
// load plugins system
include 'inc/okcdesign_plugins_manager.php';


/*=============================
   PREPROCESS
 ==============================*/

/**
 * Implements template_preprocess_page()
 *
 * Dynamic grid classes accroding to the number of displayed columns
 */
function okcdesign_preprocess_page(&$variables) {

  // by default, content is 12.
  $content_grid_classes = 'small-12 columns';
  $sidebar_first_grid_classes = 'small-12 large-2 columns';
  $sidebar_second_grid_classes = 'small-12 large-2 columns';

  // change content width according to first and seconde sidebar, if they are not empty

  // If the two sidebars are present
  if (!empty($variables['page']['sidebar_first']) && !empty($variables['page']['sidebar_second'])) {
    $content_grid_classes = 'small-12 large-6 columns';
    $sidebar_first_grid_classes = 'small-12 large-3 columns';
    $sidebar_second_grid_classes = 'small-12 large-3 columns';
  }
  // if there is only first sidebar
  elseif(!empty($variables['page']['sidebar_first']) && empty($variables['page']['sidebar_second'])) {
    $content_grid_classes = 'small-12 large-8 columns';
    $sidebar_first_grid_classes = 'small-12 large-4 columns';
  }
  // if there is only second sidebar
  elseif(empty($variables['page']['sidebar_first']) && !empty($variables['page']['sidebar_second'])) {
    $content_grid_classes = 'small-12 large-8 columns';
    $sidebar_second_grid_classes = 'small-12 large-4 columns';
  }

  // send our dynamic grid classe to the page.tpl.php template
  $variables['content_grid_classes'] = $content_grid_classes;
  $variables['sidebar_first_grid_classes'] = $sidebar_first_grid_classes;
  $variables['sidebar_second_grid_classes'] = $sidebar_second_grid_classes;

  okcdesign_plugins_dispatch(__FUNCTION__, $variables);

}

/**
 * Implements hook_css_alter()
 */
function okcdesign_css_alter(&$variables) {
  okcdesign_plugins_dispatch(__FUNCTION__, $variables);
}

/**
 * Implements hook_html_head_alter().
 */
function okcdesign_html_head_alter(&$variables) {
  okcdesign_plugins_dispatch(__FUNCTION__, $variables);
}

/*=============================
   THEME OVERRIDES
 ==============================*/

/**
 * Implements hook_theme_breadcrumb()
 */
function okcdesign_breadcrumb($variables) {
  $html = okcdesign_plugins_dispatch(__FUNCTION__, $variables);
  if ($html) return $html;
  return theme_breadcrumb($variables);
}

function okcdesign_status_messages($variables) {
  $html = okcdesign_plugins_dispatch(__FUNCTION__, $variables);
  if ($html) return $html;
  return theme_status_messages($variables);
}

