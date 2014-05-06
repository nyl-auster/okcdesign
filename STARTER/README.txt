COMPILE WITH GRUNT & LIBSASS
-----------------------------

Run following command, at the root of your subtheme :
   grunt


COMPILE WITH SASS
-------------------
Run following command at the root of your subtheme.

  sass --watch scss:css -I ../okcdesign/bower_components/foundation/scss -I ../okcdesign/scss


FOUNDATION SETTINGS
--------------------

Foundation is a css framework. To take full advantages of foundation power, you
must edit scss/_settings.scss file of your subtheme and set variables suitable
for your project :
configure grids, main colors, main radius, default displaying of all foundation elements.
