OKC Design
-------------

OKCDesign is a Drupal 7 starter theme, based mainly on zurb foundation css framework.

http://foundation.zurb.com/

It allows your subtheme to be natively responsive, using foundation grid system, 
and a clean design by default using foudation css instead of Drupal native css.

All drupal css, except those needed for administration purposes, are completely
removed.

OKC Design try to use when possible foundation mixins and scss rather than
rewriting drupal html, which is an heavy and painfull operation.

It also comes with usefull libraries, located in bower_components directory.
(this directory is maintened and updated with bower, a package front-end manager by twitter.


PLUGINS
----------

OKC Design works with plugins, which are some kind of tiny modules for your theme.
Only enabled plugins that you actually need, in theme settings; OKC design will take
care of loading associated files and code.
You may also simply edit info for that, to keep your configuration in theme info file. (recommended)


START
-----------------

To start develop, you MUST create a OKC Design subtheme.
You just need to edit app.scss in scss file to start coding with foudation library and sass.
All libraries are loaded by default from base theme to keep subtheme light and reduce
amount of files than need to be updated if you have several subthemes.

Use the following command to automatically create a subtheme, then go to theme admnistration page to set it by default :


```shell
  drush okc-theme {yourthemename}
```

COMPILE IN SUBTHEME
-------------------

Compile scss files with sass commands, run the following command at the root of your subtheme.
IMPORTANT : you MUST a load path to fetch foundation components in base theme, or it won't work.
Adapt load path to your directory structure if needed.

```shell
  sass --watch scss:css --load-path ../okcdesign/bower_components/foundation/scss
```

OKCDESIGN DEVELOPMENT
-------------------

Only if you contribute to okcdesign, the following command allow scss compilation.
Run it at the root of ockdesign theme :

```shell
  sass --watch scss:css --load-path bower_components/foundation/scss
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

