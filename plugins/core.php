<?php
/**
 * @file
 *
 * This plugin does only things required to make foundation works with Drupal.
 * http://foundation.zurb.com/docs/css.html
 */

class core {

  /**
   * Adjust drupal html headers and add css & js required by foundation
   */
  static function hook_html_head_alter(&$head_elements) {

    drupal_add_js(drupal_get_path('theme', 'okcdesign') . '/js/app.js');
    // following files will be included by the subtheme and not okcdesign
    // if a subtheme is created from drush ost command.
    drupal_add_js(drupal_get_path('theme', $GLOBALS['theme']) . '/foundation/bower_components/modernizr/modernizr.js');
    drupal_add_js(drupal_get_path('theme', $GLOBALS['theme']) . '/foundation/bower_components/foundation/js/foundation.min.js');
    drupal_add_css(drupal_get_path('theme', $GLOBALS['theme']) . '/css/app.css');
    drupal_add_css(drupal_get_path('theme', $GLOBALS['theme']) . '/foundation/icons/foundation-icons.css');

    // HTML5 charset declaration.
    $head_elements['system_meta_content_type']['#attributes'] = array(
      'charset' => 'utf-8',
    );

    // Optimize mobile viewport.
    $head_elements['mobile_viewport'] = array(
      '#type' => 'html_tag',
      '#tag' => 'meta',
      '#attributes' => array(
        'name' => 'viewport',
        'content' => 'width=device-width, initial-scale=1.0',
      ),
    );

    // Remove image toolbar in IE.
    $head_elements['ie_image_toolbar'] = array(
      '#type' => 'html_tag',
      '#tag' => 'meta',
      '#attributes' => array(
        'http-equiv' => 'ImageToolbar',
        'content' => 'false',
      ),
    );
  }

  /**
   * Remove drupal core files, except the one we actually need to work
   */
  static function hook_css_alter(&$css) {

    // do not break demdo block page
    if(strpos($_GET['q'], 'admin/structure/block/demo') === 0) return;

    // keep those css, so that overlay, shortcut, toolbar and contextual links
    // still works as expected.
    $css_to_keep = array(
      'modules/system/system.base.css',
      'modules/contextual/contextual.css',
      'modules/toolbar/toolbar.css',
      'modules/shortcut/shortcut.css',
      'modules/overlay/overlay-parent.css',
    );

    // remove all others
    foreach($css as $path => $values) {
      if(strpos($path, 'modules/') === 0 && !in_array($path, $css_to_keep)) {
        unset($css[$path]);
      }
    }

  }


}