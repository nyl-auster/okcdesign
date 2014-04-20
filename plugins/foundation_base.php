<?php
/**
 * @file
 *
 * This plugin does only things required to make foundation works with Drupal.
 * http://foundation.zurb.com/docs/css.html
 */

class foundation_base {

  /**
   * Adjust drupal html headers and add css & js required by foundation
   */
  static function okcfoundation_theme_html_head_alter(&$args) {

    $head_elements = &$args['head_elements'];

    drupal_add_js(drupal_get_path('theme', 'okcfoundation_theme') . '/foundation/bower_components/modernizr/modernizr.js');
    drupal_add_js(drupal_get_path('theme', 'okcfoundation_theme') . '/foundation/bower_components/foundation/js/foundation.min.js');
    drupal_add_js(drupal_get_path('theme', 'okcfoundation_theme') . '/js/app.js');
    drupal_add_css(drupal_get_path('theme', 'okcfoundation_theme') . '/css/app.css');

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
  static function okcfoundation_theme_css_alter(&$args) {

    $css = &$args['css'];

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