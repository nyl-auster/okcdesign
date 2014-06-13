<?php

/**
 * This plugin plug foundation css/js framework into Drupal.
 * http://foundation.zurb.com/docs/css.html
 *
 * It also removes all unecessary css files, that may interfere with foundation css.
 */
class foundation extends theme_plugin {


  /**
   * Adjust drupal html headers and add css & js required by foundation
   */
  function hook_html_head_alter(&$head_elements) {

    // by default, we will load this file
    $foundation_css = $this->default_theme_path . '/css/app.css';

    // if foundation_ui is enabled && its generated file is readable, we load user_app.css instead.
    if (theme_plugin_is_enabled('foundation_ui')) {
      $user_app_css = variable_get('file_public_path', conf_path() . '/files') . '/okcdesign/' . variable_get('theme_default', 'okcdesign') . '/user_app.css';
      if (is_readable($user_app_css)) {
        $foundation_css = $user_app_css;
      }
    }

    drupal_add_css($foundation_css);

    // for other files, use okcdesign files to not duplicate theme, for easier maintenance of all subthemes.
    drupal_add_js($this->base_theme_path . '/js/app.js');
    drupal_add_js($this->base_theme_path . '/' . $this->vendors_directory . '/modernizr/modernizr.js');
    drupal_add_js($this->base_theme_path . '/' . $this->vendors_directory . '/foundation/js/foundation.min.js');

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

  // keep those css, so that overlay, shortcut, toolbar and contextual links
  // still works as expected.
  function css_white_list() {
    return array(
      'modules/system/system.base.css',
      'modules/contextual/contextual.css',
      'modules/toolbar/toolbar.css',
      'modules/shortcut/shortcut.css',
      'modules/overlay/overlay-parent.css',
      'modules/overlay/overlay-child.css',
    );
  }

  /**
   * Remove drupal core files, except the one we actually need to work
   */
  function hook_css_alter(&$css) {

    // do not break demdo block page
    if(strpos($_GET['q'], 'admin/structure/block/demo') === 0) return;

    // remove all others
    $css_to_keep = $this->css_white_list();
    foreach($css as $path => $values) {
      if(strpos($path, 'modules/') === 0 && !in_array($path, $css_to_keep)) {
        unset($css[$path]);
      }
    }

  }

}

