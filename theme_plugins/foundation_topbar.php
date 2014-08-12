<?php

/**
 * Display choosen menus in a mobile-friendly foundation topbar.
 */
class foundation_topbar extends theme_plugin {

  /**
   * Plugin configuration form. Let user choose which menus he wants
   * to print in the main topbar.
   */
  function settings_form(&$theme_settings_form = array()) {
    $form['menu_left'] = array(
      '#type' => 'select',
      '#title' => 'Left menu',
      '#options' => array('' => '<none>') + menu_get_menus(),
      '#default_value' => theme_plugin_get_setting(__CLASS__, 'menu_left', 'main-menu'),
    );
    $form['menu_right'] = array(
      '#type' => 'select',
      '#title' => 'Right menu',
      '#options' => array('' => '<none>') + menu_get_menus(),
      '#default_value' => theme_plugin_get_setting(__CLASS__, 'menu_right', 'user-menu'),
    );
    $form['sticky'] = array(
      '#type' => 'checkbox',
      '#title' => 'Make topbar sticky at the top of the page',
      '#default_value' => theme_plugin_get_setting(__CLASS__, 'sticky'),
    );
    $form['contain_to_grid'] = array(
      '#type' => 'checkbox',
      '#title' => 'Set to grid width instead of full page width',
      '#default_value' => theme_plugin_get_setting(__CLASS__, 'contain_to_grid'),
    );
    $form['hide_site_name'] = array(
      '#type' => 'checkbox',
      '#title' => 'Hide the site name displaying by default at the left of the topbar',
      '#default_value' => theme_plugin_get_setting(__CLASS__, 'hide_site_name'),
    );
    return $form;
  }

  function hook_theme(&$themes) {
    $themes['foundation_topbar'] = array(
      'variables' => array(
        'links_right' => array() ,
        'links_left' => array()
      ),
      'template' => 'templates/foundation_topbar',
    );
    $themes['foundation_topbar_submenu'] = array(
      'variables' => array('links' => array()),
      'template' => 'templates/foundation_topbar_submenu',
    );
  }

  function hook_preprocess_foundation_topbar(&$variables) {

    foreach (element_children($variables['links_left']) as $i) {
      // php 5.4 will complain about references without this extra affectation...
      $this->add_active_class($link = &$variables['links_left'][$i]);
      // add link id for theming
      $variables['links_left'][$i]['#attributes']['id'] = 'link-' . $i;
    }

    foreach (element_children($variables['links_right']) as $i) {
      $this->add_active_class($link = &$variables['links_right'][$i]);
      // add link id for theming
      $variables['links_right'][$i]['#attributes']['id'] = 'link-' . $i;
    }
  }

  function hook_preprocess_page(&$variables) {
    $links_left = menu_tree_output(menu_tree_all_data(theme_plugin_get_setting('foundation_topbar', 'menu_left', 'main-menu')));
    $links_right = menu_tree_output(menu_tree_all_data(theme_plugin_get_setting('foundation_topbar', 'menu_right', 'user-menu')));
    $variables['foundation_topbar'] = theme('foundation_topbar', array(
      'links_left' => $links_left,
      'links_right' => $links_right)
    );
  }

  /**
   * Add missing active class, that is normally handled by theme_links...
   * @param $link
   */
  private function add_active_class(&$link) {
    global $language_url;
    global $user;
    if (isset($link['#href'])
      && ($link['#href'] == $_GET['q'] || ($link['#href'] == '<front>' && drupal_is_front_page()))
      && (empty($link['language']) || $link['language']->language == $language_url->language)
      // hack for user page
      || $_GET['q'] == 'user/' . $user->uid && $link['#href'] == 'user') {
        // victory !
        $link['#attributes']['class'][] = 'active';
    }
  }

}