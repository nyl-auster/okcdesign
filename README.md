OKC Design
-------------

**Drupal 7 + Foundation 5 : build professional responsive sites faster !**

![Logo](https://raw.githubusercontent.com/nyl-auster/okcdesign/master/screenshot.png)

OKCDesign is a Drupal 7 starter theme for developers, based on zurb foundation 5 framework :
foundation is a very powerfull front-end, mobile-first, responsive framework; check it out if you don't know it.

http://foundation.zurb.com/

It is designed to work with optionnal module "OKC Layout", which job is to help you place quickly blocks on OKC Design grids.

https://github.com/nyl-auster/okclayout


PHILOSOPHY
----------

OKC Design focus on bring all the power of foundation to your Drupal 7 theme, in order to build professional responsive themes faster.
It makes zero assumptions on what your are trying to achieve, it focus on make it easy for you to use foundation tools with Drupal 7.
So make sure to read some docs on foundation frameworks before starting if you don't know how this works at all.

FEATURES
---------

- Enjoy all the foundation goodness in Drupal 7 :  Grid system, components, responsive design etc...
- A ready-to-use integration of foundation topbar to Drupal, working out of the box. (just enable the plugin in theme settings)
- Remove all drupal native css, excepts those very needed for administration purposes
- Powerfull theme plugin system : Enabled plugins you need, configure them as needed.

REQUIREMENTS
-------------

- PHP 5.3+ (unless you like the "unexpected T_PAAMAYIM_NEKUDOTAYIM" error)
- foundation requirements to work with sass & foundation 5 : http://foundation.zurb.com/docs/sass.html :
  - Git
  - Ruby 1.9+
  - NodeJs
- jquery_update drupal module with jquery >= 1.10
- drush

INSTALLATION
-----------------

To start develop, you  *MUST* create a OKC Design subtheme. *Never* edit okcdesign files unless you wish to contribute to the project.

- Get last version of okcdesign, download a release tag (recommended) or clone master (unstable) for
  the last dev version.

- Make sure that parent theme is named "*okcdesign*" and not "okcdesign-1.x.x" if you download it from a release tag !

- Use the following drush command to automatically create a subtheme, then go to theme administration page to set it by default :

```shell
  drush okc-theme {yourthemename}
```

- Now go to your freshly created subtheme, and run following command to start working
with scss files :

```shell
  grunt
```
- scss folder from your subtheme will contain two interesting files :
  - app.scss is the main file, including all others scss files. (including _settings.scss file)
  - _settings.scss file is the file configuring all foundation default values. This is where you can configure grid, default colors, font-size and a lot more.
  - You'll have to create new scss files and import them in app.scss. Organize them the way you like.

Scss files are compiled and compressed in css directory by default, but you may edit Grunfile.js to configure things differently.

- customize html : copy templates from okcdesign/templates to your subtheme/templates (do not forget to flush drupal caches)


SCSS COMPILATION
------------------

The most efficient way is to simply type the following command at the root of your subtheme :
```shell
  grunt
```

It will read informations from Gruntfile.js and package.json to compile your project.
It uses libsass, which compile scss a lot faster than sass command.

alternatively, you can use sass, but you MUST include foundation components from okcdesign theme, this way :

```shell
  sass --watch scss:css -I ../okcdesign/bower_components/foundation/scss -I ../okcdesign/scss
```
GRID SYSTEM WITH BLOCKS SETTINGS
--------------------------------

If you are using https://github.com/nyl-auster/okcfoundation-D7 , you'll be able to quickly place blocks on the grid.
It add a textarea to register foundation classes for a block, or you may use theme info file to register your blocks layout.

a *"small-12 medium-6 large-4 columns"* class means that this block will take all the page width on small screens, half-width on medium devices and only 1/3 of page witdh on large screens.
See foundation docs on Grid for more fun stuff.

![Logo](https://raw.githubusercontent.com/nyl-auster/okcdesign/master/images/demo-regions.png)

Using okcfoundation module, you may defined different classes for different page, for the same block:
![Logo](https://raw.githubusercontent.com/nyl-auster/okcdesign/master/images/demo-block.png)



ADDITIONNAL DRUPAL MODULES
--------------------------------

There is on drupal.org modules based on foundation, they should work with this project :
- https://drupal.org/project/field_orbit
- https://drupal.org/project/zurb_clearing
- https://drupal.org/project/foundation_group
- https://drupal.org/project/zurb_interchange
- https://drupal.org/project/zurb_twentytwenty

