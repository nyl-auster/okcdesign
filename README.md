OKC Design
-------------
![Logo](https://raw.githubusercontent.com/nyl-auster/okcdesign/master/screenshot.png)

OKCDesign is a profesionnal Drupal 7 starter theme for professionnal developers, based on zurb foundation 5 framework :
foundation is a very powerfull front-end, mobile-first, responsive framework.

http://foundation.zurb.com/

OKC Design makes zero assumptions on what your are trying to achieve, it focus on make it easy to use foundation tools with Drupal 7.

Features :
- Enjoy all the foundation goodness in Drupal 7 :  Grid system, components, responsive design etc...
- A ready-to-use integration of foundation topbar to Drupal, working out of the box.
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

Now go to your freshly created subtheme, and run following command to start working
with scss files :

```shell
  grunt
```

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


