<?php

class dynamic_sidebars {

  function hook_preprocess_page(&$variables) {

    // by default, content is 12.
    $content_grid_classes = 'small-12 columns';
    $sidebar_first_grid_classes = 'small-12 large-2 columns';
    $sidebar_second_grid_classes = 'small-12 large-2 columns';

    // change content width according to first and seconde sidebar, if they are not empty

    // If the two sidebars are present
    if (!empty($variables['page']['sidebar_first']) && !empty($variables['page']['sidebar_second'])) {
      $content_grid_classes = 'small-12 large-6 columns';
      $sidebar_first_grid_classes = 'small-12 large-3 columns';
      $sidebar_second_grid_classes = 'small-12 large-3 columns';
    }
    // if there is only first sidebar
    elseif(!empty($variables['page']['sidebar_first']) && empty($variables['page']['sidebar_second'])) {
      $content_grid_classes = 'small-12 large-8 columns';
      $sidebar_first_grid_classes = 'small-12 large-4 columns';
    }
    // if there is only second sidebar
    elseif(empty($variables['page']['sidebar_first']) && !empty($variables['page']['sidebar_second'])) {
      $content_grid_classes = 'small-12 large-8 columns';
      $sidebar_second_grid_classes = 'small-12 large-4 columns';
    }

    // send our dynamic grid classe to the page.tpl.php template
    $variables['content_grid_classes'] = $content_grid_classes;
    $variables['sidebar_first_grid_classes'] = $sidebar_first_grid_classes;
    $variables['sidebar_second_grid_classes'] = $sidebar_second_grid_classes;

  }

}