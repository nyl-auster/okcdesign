<?php
/**
 * Created by PhpStorm.
 * User: yann
 * Date: 20/04/2014
 * Time: 20:07
 */

class status_messages {

  function okcfoundation_theme_status_messages($variables) {
    $display = $variables['display'];
    $output = '';

    $status_heading = array(
      'status' => t('Status message'),
      'error' => t('Error message'),
      'warning' => t('Warning message'),
      'info' => ('Status message'),
    );

    $foundation_css = array(
      'error' => 'warning',
      'status' => 'info',
      'warning' => 'warning'
    );

    foreach (drupal_get_messages($display) as $type => $messages) {
      $output .= "<div data-alert class=\"alert-box round {$foundation_css[$type]}\">\n";
      if (!empty($status_heading[$type])) {
        $output .= '<h2 class="element-invisible">' . $status_heading[$type] . "</h2>\n";
      }
      if (count($messages) > 1) {
        $output .= " <ul>\n";
        foreach ($messages as $message) {
          $output .= '  <li>' . $message . "</li>\n";
        }
        $output .= " </ul>\n";
      }
      else {
        $output .= $messages[0];
      }
      $output .= '<a href="#" class="close">&times;</a>';
      $output .= "</div>\n";
    }
    return $output;
  }

}