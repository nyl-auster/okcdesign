OKC Design
-------------

OKCDesign is a Drupal 7 starter theme, based on zurb foundation css framework.
http://foundation.zurb.com/
It allows your subtheme to be natively responsive, using foundation grid system, 
and a clean design by default using foudation css instead of Drupal native css.
OKC Design try to use when possible foundation mixins and scss rather than
rewriting drupal html, which is an heavy and painfull operation.

OKC Design try to make zero assumptions and allow developper to choose
which parts of foundation css applies to which part of Drupal. Either
by using mixins to target Drupal html, or by enabling theme "plugins" to
transform html of drupal in a specific way; but nothing is hardcoded on this side
(For example; main menu and secondary MAY be designed as a foundation navigation top bar,
but it's up to you to enable this and not forced by the theme)

START
-----------------

To start develop, you MUST create a OKC Design subtheme, which will contain
all foundation scss. You just need to edit app.scss in scss file to start coding, as you would do
without Drupal.
Use the following command to automatically create a subtheme ready to enable and use :


```shell
  drush ost {yourthemename}
```

Compile scss files with sass commands. Note that we are adding
path to foundation vendor directory, this is required for compilation to work as expected.
Run this command from your sub theme root directory :

```shell
  sass --watch scss:css --load-path foundation/bower_components/foundation/scss
```

foundation vendor directory has been installed with following command :

```shell
  foundation new foundation --libsass
```

So he can be updated easily with following command if you wish.

```shell
  foundation update
```

(@see foundation frameworks doc on how to install foundation with sass for more informations,
you'll need foundation ruby-gem, bower, nodejs etc...)


