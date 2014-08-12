<?php
/**
 * Please note that this Drupal Bootstrap script won't work in a multisite
 * Drupal installation.
 *
 * Thanks to Roman Zimmermann for this drupal bootstrap script, i only use
 * getcwd() function instead of CWD string, and add a "chdir" to the drupal root
 * at the end of the script.
 * From http://devblog.more-onion.com/content/writing-unit-tests-drupal-7
 *
 */

$path = getcwd();

$site_dir = NULL;
$dpl_dir = NULL;

while ($path != '/') {
  if (file_exists($path . '/settings.php')) {
    $site_dir = $path;
  }
  if (file_exists($path . '/index.php') && file_exists($path . '/includes/bootstrap.inc')) {
    $dpl_dir = $path;
    break;
  }
  $path = dirname($path);
}

if (!$dpl_dir) {
  echo "No drupal directory found in or above current working directory. Aborting. \n";
  exit(1);
}
if (!$site_dir) {
  $site_dir = $dpl_dir . '/sites/default';
  if (!file_exists($site_dir . '/settings.php')) {
    echo "No configured site found. Aborting.\n";
    exit(1);
  }
}

$_SERVER['HTTP_HOST'] = basename($site_dir);
$_SERVER['REMOTE_ADDR'] = '127.0.0.1';

define('DRUPAL_ROOT', $dpl_dir);
set_include_path($dpl_dir . PATH_SEPARATOR . get_include_path());
chdir($dpl_dir);
require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);