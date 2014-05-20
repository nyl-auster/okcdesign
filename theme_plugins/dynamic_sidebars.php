<?php

/**
 * Resize width of sidebars and content according to the device.
 * Sidebar left or right are printed only if they contains blocks,
 * so we take care of all use cases as best as we can.
 *
 * We offer a rather complex form as we can't be sure that the
 * grid contains only 12 columns; so we have to let use choose exactly
 * what he wants to fits its foundation grid configuration.
 */
class dynamic_sidebars extends theme_plugin {

  // default grid classes when there is only sidebar left
  protected $left_only = array(
    'sidebar' => "small-12 large-4 columns",
    'content' => "small-12 large-8 columns"
  );
  protected $right_only = array(
    'sidebar' => "small-12 large-4 columns",
    'content' => "small-12 large-8 columns"
  );
  protected $both = array(
    'sidebar' => "small-12 large-2 columns",
    'content' => 'small-12 large-8 columns',
  );
  protected $none = array(
    'sidebar' => '',
    'content' => 'small-12 columns',
  );

  /**
   * Plugin configuration form.
   */
  function settings_form(&$theme_settings_form = array()) {
    $cases = array(
      'left_only' => "When only left sidebar is displayed",
      'right_only' => "When only right sidebar is displayed",
      'both' => "When both sidebars are displayed",
      'none' => "When sidebars are not displayed at all"
    );
    foreach ($cases as $case_id => $case_name) {
      $form[$case_id] = array(
        '#type' => 'fieldset',
        '#title' => $case_name,
      );
      $case_settings = theme_plugin_get_setting(__CLASS__, $case_id, $this->$case_id);
      $form[$case_id]['sidebar'] = array(
        '#type' => 'textfield',
        '#title' => 'Sidebar foundation classes',
        '#default_value' => $case_settings['sidebar'],
        '#description' => "example : small-12 large-4 columns"
      );
      if ($case_id == 'none') {
        $form[$case_id]['sidebar']['#access'] = FALSE;
        $form[$case_id]['sidebar']['#value'] = '';
      }
      if (module_exists('okclayout')) {
        $form[$case_id]['sidebar']['#autocomplete_path'] = 'okclayout/autocomplete/foundation-classes';
      }

      $form[$case_id]['content'] = array(
        '#type' => 'textfield',
        '#title' => 'Content foundation classes',
        '#default_value' => $case_settings['content'],
        '#description' => "example : small-12 large-8 columns"
      );

      if (module_exists('okclayout')) {
        $form[$case_id]['content']['#autocomplete_path'] = 'okclayout/autocomplete/foundation-classes';
      }
    }

    return $form;
  }

  /**
   * Change dynamically classes according to user defined settings, or using
   * default values if user did not configured anything yet.
   * @param $variables
   */
  function hook_preprocess_page(&$variables) {

    $left_only = theme_plugin_get_setting(__CLASS__, 'left_only', $this->left_only);
    $right_only = theme_plugin_get_setting(__CLASS__, 'right_only', $this->right_only);
    $both = theme_plugin_get_setting(__CLASS__, 'both', $this->both);
    $none = theme_plugin_get_setting(__CLASS__, 'none', $this->none);


    // If the two sidebars are present
    if (!empty($variables['page']['sidebar_first']) && !empty($variables['page']['sidebar_second'])) {
      $sidebar_first_classes = $sidebar_second_classes = $both['sidebar'];
      $content_classes = $both['content'];
    }
    // if there is only first sidebar
    elseif(!empty($variables['page']['sidebar_first']) && empty($variables['page']['sidebar_second'])) {
      $sidebar_first_classes = $left_only['sidebar'];
      $sidebar_second_classes = '';
      $content_classes = $left_only['content'];
    }
    // if there is only second sidebar
    elseif(empty($variables['page']['sidebar_first']) && !empty($variables['page']['sidebar_second'])) {
      $sidebar_second_classes = $right_only['sidebar'];
      $sidebar_first_classes = '';
      $content_classes = $right_only['content'];
    }
    // It node sidebars are displayed
    elseif (empty($variables['page']['sidebar_first']) && empty($variables['page']['sidebar_second'])) {
      $sidebar_second_classes = $sidebar_first_classes = $none['sidebar'];
      $content_classes = $none['content'];
    }

    // send our dynamic foundation classes to the page.tpl.php template
    $variables['content_classes'] = $content_classes;
    $variables['sidebar_first_classes'] = $sidebar_first_classes;
    $variables['sidebar_second_classes'] = $sidebar_second_classes;

  }

}