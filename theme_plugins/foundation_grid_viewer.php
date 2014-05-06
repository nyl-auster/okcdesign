<?php

class foundation_grid_viewer extends theme_plugin_base {

  function settings_form() {
    $form['display_above_theme'] = array(
      '#type' => 'checkbox',
      '#title' => 'Show review grid in front of the theme',
      '#description' => 'By default, grid is displayed behind all other html elements of the site',
      '#default_value' => theme_plugin_get_setting(__CLASS__, 'display_above_theme', 0),
    );
    return $form;
  }

  function hook_preprocess_page(&$variables) {
    $settings = $this->get_foundation_settings();
    $columns = $settings['total_columns'];
    $variables['foundation_grid_viewer'] = $this->theme_grid_viewer($columns);
  }



  function theme_grid_viewer($columns) {
    $above = theme_plugin_get_setting(__CLASS__, 'display_above_theme', 0);
    $z_index = $above ? '0' : '-999';
    $html = array();
    $html[] = '<div style="z-index:' . $z_index . ';position:relative;opacity:0.5" class="row">';
    $html[] = '<div style="position:absolute;top:0;height:100%;width:100%">';
    for($i =1; $i <= $columns; $i++) {
      $html[] = '<div style="" class="small-1 columns">';
      $html[] = '<p style="height:3000px;background:#EEE;"> ' . $i . '</p>';
      $html[] = '</div>';
    }
    $html[] = '</div>';
    $html[] = '</div>';
    return implode("\r\n", $html);
  }

}