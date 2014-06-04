<?php
/**
 * Foundation style top bar
 *
 * We render first level manually to allow html customization : this
 * way it is easy to add a search form, buttons etc... inside the topbar,
 * by overriding this template.
 *
 * Submenus are then rendered recursively by foundation_topbar_submenu template.
 * (buy you may change that too overriding this template.)
 */
?>

<?php if (theme_plugin_get_setting('foundation_topbar', 'sticky')) :?>
<div class="fixed">
  <?php endif ?>

  <?php if (theme_plugin_get_setting('foundation_topbar', 'contain_to_grid')) :?>
  <div class="contain-to-grid">
    <?php endif ?>

    <nav class="top-bar" data-topbar>

      <?php // render the first level ourself, to make it super customizable by overriding this template ?>

      <ul class="title-area">

        <?php if ($site_name = variable_get('site_name', '')): ?>
          <li class="name">
            <?php if (!theme_plugin_get_setting('foundation_topbar', 'hide_site_name')) :?>
              <h1><a href="<?php print url('<front>')?>"><?php print $site_name ?></a></h1>
            <?php endif ?>
          </li>
        <?php endif ?>
      </ul>

      <li class="toggle-topbar menu-icon"><a href="#">Menu</a></li>

      <?php if ($links = $links_left) : ?>
        <section class="top-bar-section">

          <ul class="left">

            <?php foreach (element_children($links) as $i) :?>

              <?php $link = l($links[$i]['#title'], $links[$i]['#href'], $links[$i]['#localized_options']) ?>

              <?php if ($links[$i]['#below']) : ?>

                <?php $links[$i]['#attributes']['class'][] = 'has-dropdown' ?>
                <li <?php print drupal_attributes($links[$i]['#attributes']) ?>>
                  <?php print $link ?>
                  <?php print theme('foundation_topbar_submenu', array('links' => $links[$i]['#below'])) ?>
                </li>

              <?php else : ?>

                <li <?php print drupal_attributes($links[$i]['#attributes']) ?>>
                  <?php print $link ?>
                </li>

              <?php endif ?>

            <?php endforeach ?>
          </ul>
        </section>
      <?php endif ?>

      <?php if ($links = $links_right) : ?>
        <section class="top-bar-section">
          <ul class="right">
            <?php foreach (element_children($links) as $i) :?>

              <?php $link = l($links[$i]['#title'], $links[$i]['#href'], $links[$i]['#localized_options']) ?>

              <?php if ($links[$i]['#below']) : ?>

                <?php $links[$i]['#attributes']['class'][] = 'has-dropdown' ?>
                <li <?php print drupal_attributes($links[$i]['#attributes']) ?>>
                  <?php print $link ?>
                  <?php print theme('foundation_topbar_submenu', array('links' => $links[$i]['#below'])) ?>
                </li>

              <?php else : ?>

                <li <?php print drupal_attributes($links[$i]['#attributes']) ?>>
                  <?php print $link ?>
                </li>

              <?php endif ?>

            <?php endforeach ?>
          </ul>
        </section>
      <?php endif ?>

    </nav>

    <?php if (theme_plugin_get_setting('foundation_topbar', 'contain_to_grid')) :?>
  </div>
<?php endif; ?>

  <?php if (theme_plugin_get_setting('foundation_topbar', 'sticky')) :?>
</div>
<?php endif; ?>

