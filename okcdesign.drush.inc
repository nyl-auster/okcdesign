<?php
/**
 * @file
 *
 * Create drush commands to create subthemes and plugins in a quick way.
 */

/**
 * Implementation of hook_drush_command().
 */
function okcdesign_drush_command() {
  $items = array();
  $items['okcdesign-theme'] = array(
    'description' => 'Create a OKC Design sub-theme',
    'aliases' => array('okc-theme'),
    'arguments' => array(
      'name'         => 'Your sub-theme machine name. [a-z, 0-9] ',
    ),
    'examples' => array(
      'drush okc-theme "my_theme"' => 'Create a sub-theme.',
    ),
  );
  $items['okcdesign-plugin'] = array(
    'description' => 'Create a new OKC plugin',
    'aliases' => array('okc-plugin'),
    'arguments' => array(
      'name'         => 'plugin machine name',
    ),
    'options' => array(
      'hooks'  => 'list of hooks, separated with virgules',
    ),
    'examples' => array(
      'drush okc-plugin monplugin --hooks="hook_preprocess_page, hook_css_alter"' => 'Create a new plugin responding to specified hooks in plugins/others',
    ),
  );

  return $items;
}

/**
 * Create a okcdesign sub-theme.
 */
function drush_okcdesign_theme($name = NULL) {
  if (empty($name)) {
    drush_set_error(dt("Please provide a name for the sub-theme.\nUSAGE:\tdrush okc-theme [machine_name]\n"));
    return;
  }
  //Filter everything but letters, numbers, underscores, and hyphens
  $name = preg_replace('/[^a-z0-9_-]+/', '', strtolower($name));
  // Eliminate hyphens
  $name = str_replace('-', '_', $name);
  drush_print('Name of subtheme to create : ' . $name);

  // Find theme paths. For whatever reason, drupal_get_path fails sometimes, we guess
  // theme path from our drush file location...
  $okcdesign_path = dirname(__FILE__);  
  drush_print('Searching for OKC Design base theme path : ' . $okcdesign_path);
  // make sur we find an okcdesign directory, otherwise, abort.
  if (strpos($okcdesign_path, 'okcdesign') === FALSE) {
    drush_print("OKC Design path is incorrect : $okcdesign_path");  
  }
  drush_print('Searching for root of current Drupal installation : ' . drush_locate_root());
  $subtheme_path = dirname($okcdesign_path) . '/' . $name;
  drush_print('Determining subtheme path to create : ' . $subtheme_path);

  if (file_exists($subtheme_path)) {
    drush_print('theme  ' . $name . ' already exists in ' . $subtheme_path . ', canceling subtheme creation.');
    return;
  }

  drush_print('Begin copy of starter theme files into ' . $subtheme_path);
  drush_copy_dir("{$okcdesign_path}/STARTER/", "$subtheme_path", FILE_EXISTS_MERGE);

  // create directory structure
  $directories = array('scss', 'css', 'templates', 'js');
  foreach ($directories as $directory) {
    drush_op('mkdir', "$subtheme_path/$directory");
    drush_print("Creating directory $subtheme_path/$directory");
  }

  // copy some files directly from base theme
  $files = array('css/app.css', 'scss/app.scss', 'scss/_settings.scss', 'logo.png');
  foreach ($files as $file) {
    drush_op('copy', "$okcdesign_path/$file", "$subtheme_path/$file");
    drush_print("Copying $okcdesign_path/$file to $subtheme_path/$file");
  }

  // create subtheme info file
  $lines = array(
    "name = $name",
    "description = a subtheme of okcdesign",
    "base theme = okcdesign",
    "engine = phptemplate",
    "core = 7.x",
    "",
  );

  // copy regions from base theme.
  $regions = system_region_list('okcdesign', REGIONS_VISIBLE);
  foreach ($regions as $region_id => $region_name) {
    if (!in_array($region_id, array('dashboard_sidebar', 'dashboard_inactive', 'dashboard_main'))) {
      drush_print("Add $region_name to subtheme");
      $lines[] = "regions[$region_id] = $region_name";
    }
  }
  $file = "$subtheme_path/$name.info";
  drush_op('file_put_contents', $file, implode($lines, "\r\n"));

  // create gitignore file, do not track node_modules folder by default,
  $lines = array(
    "node_modules"
  );
  $file = "$subtheme_path/.gitignore";
  drush_op('file_put_contents', $file, implode($lines, "\r\n"));

  // Notify user of the newly created theme.
  drush_print(dt("\n!name sub-theme was created in !path. Enable and set default in theme administration \n",
    array(
      '!name' => $name,
      '!path' => $subtheme_path,
    )
  ));

}

/**
 * Create a new okcdesign theme plugin
 */
function drush_okcdesign_plugin($name = NULL) {

  include_once 'theme_plugins_manager/theme_plugins_manager.php';

  if (empty($name)) {
    drush_set_error(dt("Please provide a name for the plugin."));
    return;
  }

  //Filter everything but letters, numbers, underscores, and hyphens
  $name = preg_replace('/[^a-z0-9_-]+/', '', strtolower($name));
  $hooks = explode(',', drush_get_option('hooks'));
  if (empty($hooks)) {
    drush_set_error(dt("You must at least declare one hook"));
    return;
  }

  $themepath = drush_locate_root() . '/' . drupal_get_path('theme', 'okcdesign');

  // create class in "others" directory
  $lines = array();
  $lines[] = '<?php';
  $lines[] = "";
  $lines[] = "class $name extends theme_plugin {";
  foreach ($hooks as $hook) {
    $lines[] = "";
    $lines[] = '  function ' . $hook . '(&$variables) {';
    $lines[] = '';
    $lines[] = '  }';
  }
  $lines[] = '';
  $lines[] = '}';

  $file = $themepath . '/' . OKC_THEME_PLUGINS_DIRECTORY . '/' . $name . '.php';
  drush_op('file_put_contents', $file, implode($lines, "\r\n"));


  // declare plugin in the plugin registry
  $lines = array();
  $lines[] = sprintf('$plugins[\'%s\'] = array(', $name);
  $lines[] = "  'title' => '$name',";
  $lines[] = "  'hooks' => array(";
  foreach ($hooks as $hook) {
    $lines[] = "    '$hook',";
  }
  $lines[] = '  ),';
  $lines[] = ');';
  $lines[] = '';
  $lines[] = 'return $plugins;';

  $file = $themepath . '/' . OKC_THEME_PLUGINS_REGISTRY_FILE;
  $file_contents = drush_op('file_get_contents', $file);
  $file_contents = str_replace('return $plugins;', '', $file_contents);
  $file_contents .= implode($lines, "\n");
  drush_op('file_put_contents', $file, $file_contents);

  // Notify user of the newly created theme.
  drush_print(dt("\n!name plugin was created. \n",
    array(
      '!name' => $name,
    )
  ));


}

/**
 * Internal helper: Replace strings in a file.
 */
function okcdesign_file_str_replace($file_path, $find, $replace) {
  $file_contents = file_get_contents($file_path);
  $file_contents = str_replace($find, $replace, $file_contents);
  file_put_contents($file_path, $file_contents);
}

/**
 * Implements hook_drush_help().
 */
function okcdesign_drush_help($section) {
  switch ($section) {
    case 'drush:foundation-okc-sub-theme':
      return dt("Create an OKC Design foundation custom sub-theme.");
  }
}
