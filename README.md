OKC Design
-------------

OKCDesign is a Drupal 7 starter theme for developers, based on zurb foundation 5 framework :
http://foundation.zurb.com/

Use "drush okc-theme yourthemename" to create a subtheme and start working.
*Never* edit okcdesign files unless you wish to contribute to the project.

Features :
- Get all the power of foundation :  Grid system, components, responsive design etc...
- a ready-to-use integration of foundation topbar to Drupal, working out of the box.
- Remove all drupal native css, excepts those very needed for administration purposes
- Powerfull theme plugin system : Enabled plugins you need, configure them as needed.

REQUIREMENTS
-------------

To get the best from okcdesign and recompile scss yourself, you'll need several things :
- foundation requirements to work with sass : http://foundation.zurb.com/docs/sass.html
  - Git
  - Ruby 1.9+
  - NodeJs

jquery_update drupal module with jquery >= 1.10

SCSS COMPILATION
------------------

simply run "grunt".
It will read informations from Gruntfile.js and package.json to compile your project.
It uses libsass, which compile scss a lot faster than sass command.


START
-----------------

To start develop, you  *MUST* create a OKC Design subtheme.

Use the following drush command to automatically create a subtheme, then go to theme administration page to set it by default :


```shell
  drush okc-theme {yourthemename}
```

Now go to your freshly created subtheme, and run following command to start working
with scss files :

```shell
  grunt
```


CONTRIBUTE TO OKCDESIGN DEVELOPMENT
------------------------------------

Only if you contribute to okcdesign, the following command allow scss compilation.
Run it at the root of ockdesign theme :

```shell
  grunt
```

there is a drush command to create a new plugin, creating needed files, code and
updating theme info file as needed. For example, if we want a plugin
responding to hook_preprocess_page and hook_html_head_alter :

```shell
  drush okc-plugin pluginname --hooks="hook_preprocess_page, hook_html_head_alter"
```

Plugin will appear automatically in admin settings page, ready to be enabled.

DRUPAL MODULES 
-------------------

use module block_classes or okcfoundation-D7 in this repository if you want
to place block in the grid from block configuration pages.
okcfoundation-D7 is a fork of block_classes, adding the possibility
to stock your blocks classes in the theme info file; and to set different
classes for different pages.

