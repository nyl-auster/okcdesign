<?php
/**
 * Declare our plugins and their subscribed hooks.
 */

$plugins['core'] = array(
  'hooks' => array('hook_html_head_alter', 'hook_css_alter')
);

$plugins['breadcrumb'] = array(
  'hooks' => array('hook_breadcrumb')
);

$plugins['menu_local_tasks__sub_nav'] = array(
  'hooks' => array('hook_menu_local_tasks', 'hook_menu_local_task'),
);

$plugins['status_messages__alert_box'] = array(
  'hooks' => array('hook_status_message'),
);

$plugins['system_main_menu__buttons_group'] = array(
  'hooks' => array('hook_links__system_main_menu'),
);

return $plugins;