<?php
/**
 * Plugins registry, all plugins have to be declared in this file.
 * @TODO disable checkbox for plugins that are required by enabled plugins.
 *
 * All plugins must declare the following metadatas :
 * - title : human readable name of the plugin
 * - description : what the plugin do
 * - package : plugins are grouped by package in theme settings administration
 * - hooks : on which hooks this plugin must be invoked. Plugin class then must implement a method
 *   witch exactly this name.
 * - expert : if TRUE, plugin will be invisible unless we add "/expert" at the end of url.
 *   in plugin administration form.
 * - dependencies : plugins required by plugin to work as expected.
 * - required_components : foundation components that MUST be imported in app.scss file
 *   for plugin to work.
 */

$plugins['foundation'] = array(
  'title' => 'Foundation Core',
  'description' => 'Plug foundation css framework into Drupal.',
  'package' => 'foundation',
  'expert' => TRUE,
  'hooks' => array(
    'hook_html_head_alter',
    'hook_css_alter',
  ),
);
$plugins['foundation_ui'] = array(
  'title' => 'Global design',
  'description' => 'Change design global settings. <br /><strong> Warning : </strong> When modifying this settings, all scss are recompiled to css, this operation may take some times. <br/>
  Also note that when this plugin is enabled, a special app.css file is created and used in your drupal files directory. So working with sass files directly in your theme directory will not work anymore until you disable this plugin',
  'dependencies' => array('foundation'),
  'package' => 'foundation',
);
$plugins['foundation_alert_box'] = array(
  'title' => 'Status messages',
  'description' => 'Status messages as foundation alert boxes',
  'hooks' => array('hook_status_messages'),
  'dependencies' => array('foundation'),
  'package' => 'foundation',
  'expert' => TRUE,
);
$plugins['foundation_breadcrumb'] = array(
  'title' => 'Breadcrumb',
  'description' => 'Drupal breadcrumb as foundation breadcrumb',
  'expert' => TRUE,
  'hooks' => array(
    'hook_breadcrumb'
  ),
  'dependencies' => array('foundation'),
  'package' => 'foundation',
);
$plugins['foundation_topbar'] = array(
  'title' => 'Top bar',
  'description' => 'Display drupal menus of your choice in a mobile-friendly top bar',
  'expert' => TRUE,
  'hooks' => array(
    'hook_theme',
    'hook_preprocess_foundation_topbar',
    'hook_preprocess_page',
  ),
  'dependencies' => array('foundation'),
  'package' => 'foundation',
);
$plugins['foundation_grid_viewer'] = array(
  'title' => 'Grid viewer',
  'description' => 'Display a preview of foundation grid',
  'hooks' => array(
    'hook_preprocess_page',
  ),
  'dependencies' => array('foundation'),
  'package' => 'foundation',
);
$plugins['dynamic_sidebars'] = array(
  'title' => 'Sidebars',
  'description' => 'Adjust content width according to sidebars',
  'hooks' => array('hook_preprocess_page'),
  'dependencies' => array('foundation'),
  'package' => 'foundation',
  'required' => TRUE,
  'expert' => TRUE,
);
$plugins['homepage_remove_title'] = array(
  'title' => 'Remove homepage title',
  'description' => 'Set no title on homepage',
  'hooks' => array(
    'hook_preprocess_page',
  ),
  'package' => 'others',
);
$plugins['homepage_remove_default_content'] = array(
  'title' => 'Remove homepage default content',
  'description' => 'Set no default content on homepage',
  'hooks' => array(
    'hook_preprocess_page',
  ),
  'package' => 'others',
);
$plugins['animate_css'] = array(
  'title' => 'Animate Css',
  'description' => 'Add animate css libary from Daniel Eden. Add class css to your markup to animate them !',
  'hooks' => array(
    'hook_html_head_alter',
  ),
  'package' => 'others',
  'expert' => TRUE,
);
$plugins['foundation_icon_fonts'] = array(
  'title' => 'foundation_icon_fonts',
  'description' => 'Set icons using foundation special fonts',
  'hooks' => array(
    'hook_html_head_alter'
  ),
  'dependencies' => array('foundation'),
  'package' => 'foundation',
  'expert' => TRUE,
);
$plugins['foundation_check_requirements'] = array(
  'title' => 'check_requirements',
  'hooks' => array(
    'hook_preprocess_page',
  ),
  'dependencies' => array('foundation'),
  'package' => 'foundation',
  'expert' => TRUE,
);
$plugins['foundation_pager'] = array(
  'title' => 'foundation_pager',
  'description' => 'Use foundation pager instead of drupal pager',
  'dependencies' => array('foundation'),
  'expert' => TRUE,
  'hooks' => array(
    'hook_pager',
  ),
);

return $plugins;