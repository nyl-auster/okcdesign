Compile with "Grunt"

just type "grunt" at the root of your base theme.
Grunt is much faster than sass classic compilation.

Compile with Sass :
You have to specify where is base_theme to include its foundation components, this way :

sass --watch scss:css -I ../okcdesign/bower_components/foundation/scss -I ../okcdesign/scss

