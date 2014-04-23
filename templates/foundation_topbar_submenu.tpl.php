<?php
/**
 * Foundation style top bar
 */

?>

<ul class="dropdown">
  <?php foreach (element_children($links) as $i) :?>

    <?php $link = l($links[$i]['#title'], $links[$i]['#href'], $links[$i]['#localized_options']) ?>

    <?php if ($links[$i]['#below']) : ?>

      <?php print theme('foundation_topbar_dropdown', array('links' => $links[$i]['#below'])) ?>

    <?php else : ?>

      <li <?php print drupal_attributes($links[$i]['#attributes']) ?>>
        <?php print $link ?>
      </li>

    <?php endif ?>

  <?php endforeach ?>
</ul>
