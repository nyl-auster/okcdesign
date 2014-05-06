<?php

class theme_links_helper extends theme_plugin_base {

  public $global_wrapper_markup = 'div';
  public $global_wrapper_attributes = array();

  public $list_wrapper_markup = 'ul';
  public $list_wrapper_attributes = array();

  public $list_markup = 'li';
  public $list_attributes = array();

  public $link_attributes = array();


  function render($variables) {

    $links = $variables['links'];
    $attributes = $variables['attributes'];
    $heading = $variables['heading'];
    global $language_url;
    $output = '';

    if (count($links) > 0) {
      $output = sprintf('<%s %s>', $this->global_wrapper_markup, drupal_attributes($this->global_wrapper_attributes));

      // Treat the heading first if it is present to prepend it to the
      // list of links.
      if (!empty($heading)) {
        if (is_string($heading)) {
          // Prepare the array that will be used when the passed heading
          // is a string.
          $heading = array(
            'text' => $heading,

            // Set the default level of the heading.
            'level' => 'h2',
          );
        }
        $output .= '<' . $heading['level'];
        if (!empty($heading['class'])) {
          $output .= drupal_attributes(array('class' => $heading['class']));
        }
        $output .= '>' . check_plain($heading['text']) . '</' . $heading['level'] . '>';
      }

      // @okc set default attributes
      $output .= sprintf('<%s %s>', $this->list_wrapper_markup, drupal_attributes($this->list_wrapper_attributes));

      $num_links = count($links);
      $i = 1;

      foreach ($links as $key => $link) {
        $class = array($key);

        // Add first, last and active classes to the list of links to help out themers.
        if ($i == 1) {
          $class[] = 'first';
        }
        if ($i == $num_links) {
          $class[] = 'last';
        }
        if (isset($link['href']) && ($link['href'] == $_GET['q'] || ($link['href'] == '<front>' && drupal_is_front_page())) && (empty($link['language']) || $link['language']->language == $language_url->language)) {
          $class[] = 'active';
        }

        // @okc : add our custom attributes, and merge it with default ones
        $list_attributes = $this->list_attributes;
        foreach ($class as $name) {
          $list_attributes['class'][] = $name;
        }

        $output .= '<' . $this->list_markup . drupal_attributes($list_attributes) . '>';

        if (isset($link['href'])) {
          // Pass in $link as $options, they share the same keys.
          if (isset($this->link_attributes['class'])) {
            foreach($this->link_attributes['class'] as $class_name) {
              $link['attributes']['class'][] = $class_name;
            }
          }

          $output .= l($link['title'], $link['href'], $link);
        }
        elseif (!empty($link['title'])) {
          // Some links are actually not links, but we wrap these in <span> for adding title and class attributes.
          if (empty($link['html'])) {
            $link['title'] = check_plain($link['title']);
          }
          $span_attributes = '';
          if (isset($link['attributes'])) {
            $span_attributes = drupal_attributes($link['attributes']);
          }
          $output .= '<span' . $span_attributes . '>' . $link['title'] . '</span>';
        }

        $i++;
        $output .= "</". $this->list_markup . ">\n";
      }

      $output .= '</' . $this->list_wrapper_markup . '>';
      $output .= '</' . $this->global_wrapper_markup . '>';
    }

    return $output;
  }
}