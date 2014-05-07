<?php

/**
 * Add foundation icons font to the theme.
 * This allow to use foundation icon fonts with html markup :
 * <i class="fi-mail"><i>
 */
class foundation_icon_fonts extends theme_plugin {

  function hook_html_head_alter(&$variables) {
    drupal_add_css($this->base_theme_path . '/' . $this->vendors_directory .'/foundation-icon-fonts/foundation-icons.css');
  }

}

