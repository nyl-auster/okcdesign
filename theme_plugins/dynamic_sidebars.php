<?php
/**
 * Change sidebars width dynamically, using foundation grid classes.
 * Class dynamic_sidebars
 */

class dynamic_sidebars extends theme_plugin_base {

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

  function settings_form() {

    $cases = array(
      'left_only' => "When only left sidebar is present",
      'right_only' => "When only right sidebar is present",
      'both' => "When both sidebars are presents",
      'none' => "No sidebar is present at all"
    );
    foreach ($cases as $case_id => $case_name) {
      $form[$case_id] = array('#type' => 'fieldset', '#title' => $case_name);
      $case_settings = theme_plugin_get_setting(__CLASS__, $case_id, $this->$case_id);
      $form[$case_id]['sidebar'] = array(
        '#type' => 'textfield',
        '#title' => 'Foundation classes : sidebar',
        '#default_value' => $case_settings['sidebar'],
        '#description' => "example : small-12 large-4 columns"
      );
      $form[$case_id]['content'] = array(
        '#type' => 'textfield',
        '#title' => 'Foundation classes : content',
        '#default_value' => $case_settings['content'],
        '#description' => "example : small-12 large-8 columns"
      );
    }

    return $form;
  }

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
    elseif (empty($variables['page']['sidebar_first']) && empty($variables['page']['sidebar_second'])) {
      $sidebar_second_classes = $sidebar_first_classes = $none['sidebar'];
      $content_classes = $none['content'];
    }

    // send our dynamic grid classes to the page.tpl.php template
    $variables['content_classes'] = $content_classes;
    $variables['sidebar_first_classes'] = $sidebar_first_classes;
    $variables['sidebar_second_classes'] = $sidebar_second_classes;

  }

}