<?php

/**
 * Display foundation grid above the theme.
 */
class foundation_grid_viewer extends theme_plugin {

  protected $total_columns = 12;

  function __construct() {
    // find the number of columns of current grid.
    // take the number of columns set in _settings.scss if there is one.
    // if we find nothing in _settings.scss, take the settings saved by our plugin configuration form.
    // If form has not been saved yet, let's say there is 12 columns which is default value in foudation.

    $settings = $this->get_foundation_settings();

    // is user defined custom settings for foundation via foundation_ui plugin, take this value.
    $total_columns = theme_plugin_get_setting('foundation_ui', 'total-columns');
    if ($total_columns && theme_plugin_is_enabled('foundation_ui')) {
      $this->total_columns = $total_columns;
    }
    // else if we find number of columns in _settings.scss file, take this one.
    elseif (is_numeric($settings['total_columns'])) {
      $this->total_columns = $settings['total_columns'];
    }
    // if we can't find nothing of all that, take value register by this plugin.
    else {
      $this->total_columns = theme_plugin_get_setting(__CLASS__, 'total_columns', 12);
    }
  }

  function settings_form(&$theme_settings_form = array()) {

    $expert = arg(4) == 'expert' ? TRUE : FALSE;

    $form['display_above_theme'] = array(
      '#type' => 'checkbox',
      '#title' => 'Show review grid in front of the theme',
      '#description' => 'Grid may be hide by other html elements, enable this to put grid in front of other html elements.',
      '#default_value' => theme_plugin_get_setting(__CLASS__, 'display_above_theme', 0),
    );
    $form['grid_color'] = array(
      '#type' => 'textfield',
      '#title' => 'Change grid color',
      '#description' => 'Enter a valid css color',
      '#default_value' => theme_plugin_get_setting(__CLASS__, 'grid_color', '#DDD'),
    );
    $form['display_gutters'] = array(
      '#type' => 'checkbox',
      '#title' => 'Color grid gutters',
      '#default_value' => theme_plugin_get_setting(__CLASS__, 'display_gutters', 0),
    );
    $form['grid_gutters_color'] = array(
      '#type' => 'textfield',
      '#title' => 'Change grid gutter color',
      '#description' => 'Enter a valid css color',
      '#default_value' => theme_plugin_get_setting(__CLASS__, 'grid_gutters_color', '#EEE'),
    );
    $form['total_columns'] = array(
      '#access' => $expert,
      '#type' => "textfield",
      '#title' => 'Number of columns',
      '#default_value' => theme_plugin_get_setting(__CLASS__, 'total_columns', $this->total_columns),
    );
    if (!$expert) {
      $form['total_columns_info'] = array(
        '#type' => 'item',
        "#markup" => "<strong>Your grid has currently $this->total_columns columns </strong>",
      );
    }
    return $form;
  }

  /**
   * Create a variable in page.tpl.php to display the grid.
   */
  function hook_preprocess_page(&$variables) {
    $variables['foundation_grid_viewer'] = $this->theme_grid_viewer($this->total_columns);
  }

  /**
   * Fake theme functions to display the grid.
   */
  function theme_grid_viewer($columns) {
    $above = theme_plugin_get_setting(__CLASS__, 'display_above_theme', 0);
    $display_gutters = strip_tags(theme_plugin_get_setting(__CLASS__, 'display_gutters', 0));

    $grid_color = strip_tags(theme_plugin_get_setting(__CLASS__, 'grid_color', '#DDD'));

    $columns_css = '';
    if ($display_gutters) {
      $grid_gutters_color = strip_tags(theme_plugin_get_setting(__CLASS__, 'grid_gutters_color', '#EEE'));
      $columns_css = "background : $grid_gutters_color";
    }

    $z_index = $above ? '1' : '0';
    $html = array();
    $html[] = '<div style="z-index:' . $z_index . ';position:relative;opacity:0.5" class="row">';
    $html[] = '<div style="position:absolute;top:0;height:100%;width:100%">';
    for($i = 1; $i <= $columns; $i++) {
      $html[] = '<div style="' . $columns_css . '" class="small-1 columns">';
      $html[] = '<p style="text-align:center;color:white;height:3000px;padding-top:300px;font-weight:bold;background: ' . $grid_color . ';"> ' . $i . '</p>';
      $html[] = '</div>';
    }
    $html[] = '</div>';
    $html[] = '</div>';
    return implode("\r\n", $html);
  }

}