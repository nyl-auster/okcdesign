This directory contains code to make drupal a good friend of foundation framework.

To make theme work together, we have two possibilities :
- Change drupal html markup to feet foundation css and js : this what "html" directory is for.
- Adapt foundation css and mixins to drupal markup : see scss folder.

To use html plugins, you have to enabled theme in theme info file.
To use scss plugins, you have to import theme in app.scss file as scss partials.

For some elements (like menus) you may have the choice between several plugins
(for example, you may want to render main menu as a top bar, or as group buttons etc...),
so the idea here is to let you choose which part of foundation framework you want
to apply to which part of Drupal.

Default theme is only a configuration of default plugins applied.