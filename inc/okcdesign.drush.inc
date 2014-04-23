<?php
/**
 * @file
 * Contains functions only needed for drush integration.
 *
 * Coming from zurb-foundation drupal theme
 *
 * - Create a new directory for the subtheme with
 *   - new info file
 *   - new template.php file
 *   - a copy of okcdesign/foundation folder
 *   - a copy of scss folder
 *   - a copy of css folder
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
      'name'         => 'Your sub-theme name.',
      'machine_name' => 'A machine-readable name for your theme, optional only  [a-z, 0-9] ',
    ),
    'options' => array(
      'description'  => 'Your sub-theme description.',
      'machine-name' => '[a-z, 0-9] A machine-readable name for your theme.',
    ),
    'examples' => array(
      'drush fst "custom theme name"' => 'Create a sub-theme with the default options.',
      'drush fst "foo bar" "foo_bar"  --description="My supersweet awesome theme"' => 'Create a sub-theme with additional options.',
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
      'drush osp monplugin --hooks="hook_preprocess_page, hook_css_alter"' => 'Create a new plugin responding to specified hooks in plugins/others',
    ),
  );

  return $items;
}

/**
 * Create a Zurb foundation sub-theme.
 */
function drush_okcdesign_theme($name = NULL, $machine_name = NULL, $description = NULL) {
  if (empty($name)) {
    drush_set_error(dt("Please provide a name for the sub-theme.\nUSAGE:\tdrush ost [name] [machine_name !OPTIONAL] [description !OPTIONAL]\n"));
    return;
  }
  //Filter everything but letters, numbers, underscores, and hyphens
  $machine_name = !empty($machine_name) ? preg_replace('/[^a-z0-9_-]+/', '', strtolower($machine_name)) : preg_replace('/[^a-z0-9_-]+/', '', strtolower($name));
  // Eliminate hyphens
  $machine_name = str_replace('-', '_', $machine_name);

  // Find theme paths.
  $okcdesign_path = drush_locate_root() . '/' . drupal_get_path('theme', 'okcdesign');
  $subtheme_path = dirname($okcdesign_path) . '/' . $machine_name;

  // create subtheme directory
  drush_op('mkdir', $subtheme_path);
  // paste foundation framework files inside it
  drush_copy_dir("$okcdesign_path/foundation", "$subtheme_path/foundation");

  // add info file (copy pasted from base theme for now
  drush_op('copy', "$okcdesign_path/okcdesign.info", "$subtheme_path/$machine_name.info");
  $file = drush_op('file_get_contents', "$subtheme_path/$machine_name.info");
  $content = "base theme = okcdesign\r\n" . $file;
  $content = str_replace('OKC Design', $machine_name, $content);
  drush_op('file_put_contents', "$subtheme_path/$machine_name.info", $content);

  // create folders
  drush_op('mkdir', "$subtheme_path/css");
  drush_op('copy', "$okcdesign_path/css/app.css", "$subtheme_path/css/app.css");

  drush_op('mkdir', "$subtheme_path/scss");
  drush_op('copy', "$okcdesign_path/scss/app.scss", "$subtheme_path/scss/app.scss");
  drush_op('copy', "$okcdesign_path/scss/_settings.scss", "$subtheme_path/scss/_settings.scss");
  drush_op('copy', "$okcdesign_path/scss/_mixins.scss", "$subtheme_path/scss/_mixins.scss");
  drush_op('copy', "$okcdesign_path/logo.png", "$subtheme_path/logo.png");

  // copy templates
  drush_copy_dir("$okcdesign_path/templates", "$subtheme_path/templates");


  drush_op('mkdir', "$subtheme_path/js");

  drush_pm_enable_validate($machine_name);
  drush_pm_enable($machine_name);


  // Notify user of the newly created theme.
  drush_print(dt("\n!name sub-theme was created in !path and enabled \n",
    array(
      '!name' => $machine_name,
      '!path' => $subtheme_path,
    )
  ));


}

/**
 * Create a Zurb foundation sub-theme.
 */
function drush_okcdesign_plugin($name = NULL) {
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
  $datas = array();
  $datas[] = '<?php';
  $datas[] = "";
  $datas[] = "class $name {";
  foreach ($hooks as $hook) {
    $datas[] = "";
    $datas[] = '  function ' . $hook . '(&$variables) {';
    $datas[] = '';
    $datas[] = '  }';
  }
  $datas[] = '';
  $datas[] = '}';

  $file = "$themepath/plugins/others/$name.php";
  drush_op('file_put_contents', $file, implode($datas, "\r\n"));


  $datas = array();
  $datas[] = 'okcdesign_plugins[' . $name . '][title] = ' . $name;

  foreach ($hooks as $hook) {
    $datas[] = 'okcdesign_plugins[' . $name . '][hooks][] = ' . $name;
  }
  $datas[] = '';
  $datas[] = '';

  $file = $themepath . '/okcdesign.info';
  drush_op('file_put_contents', $file, implode($datas, "\n"), FILE_APPEND);

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
