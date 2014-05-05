OKC Design
-------------

OKCDesign is a Drupal 7 starter theme, coming with the powefull zurb foundation 5 framework : http://foundation.zurb.com/
So this is highly recommended to use sass, even if theme comes with a default working css file.

Features :
- Responsive design based on foundation grid system
- Apply foundation default css to drupal html
- powerful topbar navigation for all devices out of the box
- Zero drupal css on your way, only very needed css for administration purposes have been kept.
- Powerfull theme plugin system : enable plugin and configure only plugins you need for your project.

REQUIREMENTS
-------------

jquery_update with jquery >= 1.10


START
-----------------

To start develop, you  *MUST* create a OKC Design subtheme.

Use the following drush command to automatically create a subtheme, then go to theme administration page to set it by default :


```shell
  drush okc-theme {yourthemename}
```

Then go to your freshly created subtheme, and run following command with sass to start working
with scss files :

```shell
  sass --watch scss:css -I ../okcdesign/bower_components/foundation/scss -I ../okcdesign/scss
```
The "-I" options is mandatory to load foundation components from okcdesign base theme.
This way you can build several subtheme without having to maintains several instances of foundation framework.


PLUGINS
----------

OKC Design use a plugin system : go to settings of the theme and enable / disabled plugins.
Plugin ca do anything, from removing page title from home page to include powerfull libraries
like foundation or animate.css


CONTRIBUTE TO OKCDESIGN DEVELOPMENT
------------------------------------

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

