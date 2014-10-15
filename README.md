OKC Design
-------------

**Drupal 7 + Foundation 5**

![Logo](https://raw.githubusercontent.com/nyl-auster/okcdesign/master/screenshot.png)

OKCDesign is a Drupal 7 responsive starter theme for developers, based on zurb foundation 5 framework.
Foundation 5 is a powerfull front-end, mobile-first, responsive framework : http://foundation.zurb.com/

This is a theme for developpers only, and a good knowledge of how foundation framework is required.
Only grid components is included by default, it's up to you to import components you need
in your subtheme scss/app.scss file.

By default, OKC Design takes care of displaying all drupal elements inside a solid foundation grid system for page.tpl.php;
to let foundation handle the responsive part of your theme.

FEATURES
---------

- Enjoy all the foundation goodness in Drupal 7 :  Grid system, components, responsive design, icons etc...
- A ready-to-use integration of foundation mobile-friendly topbar to Drupal. (import components in app.scss file then enable the plugin in your theme settings)
- Zero Drupal css : only css needed for administration purposes have been kept.
- Theme plugin system : Enable only plugins you need and configure them.
- Place blocks quickly on the grid with optional okclayout module : https://github.com/nyl-auster/okclayout

PHILOSOPHY
----------

OKC Design focuses on make it easy to use foundation tools with Drupal 7, but it's up to the developper to choose which parts of foundation he wants to use.

If you don't know at all foundation, you should at least visit this two pages :
- learn how to use foundation grid system : http://foundation.zurb.com/docs/components/grid.html
- overview of default foundation components : http://foundation.zurb.com/docs/components/kitchen_sink.html

You should not try to rewrite Drupal html to suit foundation css, instead use foundation mixins and adapt them to your drupal markup.

THEME PLUGINS
-------------

Visit admin/appearance/settings to enable or disable theme plugins for your subtheme.
Technically, foundation itself is provided as a plugin, enabled by default.
Thinks plugins as modules for a theme, adding features to the theme.

Visite admin/appearance/settings/{yourtheme}/expert to configure advanced plugins for OKC Design.

REQUIREMENTS
-------------

- PHP >= 5.2 
- Sass 3.3.4 (Maptastic Maple)
- foundation 5 with sass : http://foundation.zurb.com/docs/sass.html :
  - npm install -g bower grunt-cli
  - gem install foundation
  (foundation requirements :
    - Git
    - Ruby 1.9+
    - NodeJs
  )
- jquery_update drupal module with jquery >= 1.10
- drush

INSTALLATION
-----------------

To start develop, you  *MUST* create a OKC Design subtheme. *Never* edit okcdesign files unless you wish to contribute to the project.

- Get last version of okcdesign, download a release tag (recommended) or clone master (unstable) for
  the last dev version.

- Make sure that the downloaded theme is named "*okcdesign*" and not "okcdesign-1.x.x" if you download it from a release tag !

- Run following Drush command : it will create a subtheme for you 

```shell
  drush okc-theme {yourthemename}
```
- Now go to admin/appearance and set this subtheme as your default site theme.

SCSS COMPILATION
------------------------------------

To compile your scss with "grunt" (recommended) :
You have to install node and grunt-cli to make this work :
(i'm using brew package manager on mac here, use your favorite package manager)

```shell
  brew install node
  npm install -g grunt-cli
```

Go to the **root** of your freshly created subtheme and run this command, ut will install required node modules. You have to run it only the first time : 

```shell
  npm install
```

Then, run following command to compile automically your scss files each time they are modified :

```shell
  grunt
```

It will read informations from Gruntfile.js to compile your project.
It uses libsass, which compile scss a lot faster than default sass command.

alternatively, you can still use sass, but you have to include foundation components from okcdesign theme, this way :

```shell
  sass --watch scss:css -I ../okcdesign/bower_components/foundation/scss -I ../okcdesign/scss
```
Scss directory from your subtheme will contain two very important files :
  - app.scss is the main file, including all others scss files. (including _settings.scss file) . You have to import your custom scss files here.
  - _settings.scss file is the file configuring all foundation default values. This is where you can configure grid, default colors, font-size and a lot more.

IMPORTANT :
It makes the assumption than your subtheme is at the same directory level than OKC Design base theme.
If this is not the case (working in a multisite installation for example), you must edit Grunt js file or change include path of your sass command to specify the path of okcdesign theme.

GRID SYSTEM WITH BLOCKS SETTINGS
--------------------------------

Using https://github.com/nyl-auster/okclayout , you'll be able to quickly place blocks on the default OKC Design grid.
It add a textarea to register foundation classes for a block, or you may use theme info file to register your blocks layout.

a *"small-12 medium-6 large-4 columns"* class means that this block will take all the page width on small screens, half-width on medium devices and only 1/3 of page witdh on large screens.
See foundation docs on Grid for more fun stuff.

![Logo](https://raw.githubusercontent.com/nyl-auster/okcdesign/master/images/demo-regions.png)

![Logo](https://raw.githubusercontent.com/nyl-auster/okcdesign/master/images/demo-block.png)

ADDITIONNAL DRUPAL MODULES
--------------------------------

There is on drupal.org modules based on foundation, they MAY work with this project :
- https://drupal.org/project/field_orbit
- https://drupal.org/project/zurb_clearing
- https://drupal.org/project/foundation_group
- https://drupal.org/project/zurb_interchange
- https://drupal.org/project/zurb_twentytwenty

PHPUNITs test
------------------------------------

OKC Design uses PHPUnit tests, located in tests directory at the root of okcdesign theme.

