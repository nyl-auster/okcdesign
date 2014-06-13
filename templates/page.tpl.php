<?php
/**
 * @file page.tpl.php
 * @see modules/system/page.tpl.php for drupal documentation of a page.tpl.php
 *
 * This is an example of how to use grid system with foundation framework.
 * Feel free to copy this template in your subtheme templates directory and
 * overrides it as you need.
 *
 * This templates take care of placing all drupal elements inside foundation
 * grid; so that changing grid settings affect all elements of this template.
 *
 * There is custom regions suffixed by __row_xx : they are prepared to receive
 * blocks containing grid classes, if you use okclayout module.
 *
 * class "row" is a special class dedicated to foundation framework.
 * Please @see http://foundation.zurb.com/docs/components/grid.html on how to use.
 *
 * class "row-wrapper" is not a part of foundation, only a helper class for css
 * specific to this template example.
 *
 * Sidebars are handled by foundation grid classes, that may be configured
 * in theme settings if needed. (if you change the number of grid columns, you'll
 * need to ajdust their width in theme plugin settings.
 *
 * The grid settings can be configured in _settings.scss file.
 *
 * Alternatively, you may wish to keep your html semantic with foundation grid mixins
 * to define rows and columns in your scss, rather than in html classes.
 */
?>

<?php // Display the grid if needed. You need to enable Grid viewer in theme settings. ?>
<?php if (isset($foundation_grid_viewer)) : ?>
  <?php print $foundation_grid_viewer ?>
<?php endif ?>

<?php //display topbar, if foundation_topbar plugin is enabled in theme settings. ?>
<?php if(isset($foundation_topbar)) :?>
  <?php print $foundation_topbar ?>
<?php endif ?>

<header>

  <!-- site name , logo & slogan -->
  <?php if ($site_name || $logo || $site_slogan) : ?>
    <div class="row">
      <div class="columns">
        <div  id="site-informations">

          <h1>
            <?php if ($logo): ?>
              <a href="<?php print $front_page ?>" title="<?php print t('Home') ?>" rel="home" id="logo">
                <img src="<?php print $logo; ?>" alt="<?php print t('Home') ?>" />
              </a>
            <?php endif ?>

            <?php if($site_name): ?>
              <a href="<?php print $front_page; ?>" title="<?php print t('Home'); ?>" rel="home" id="site-name"> <?php print $site_name ?> </a>
            <?php endif ?>
          </h1>

          <?php if ($site_slogan): ?> <h2 id="site-slogan"><?php print $site_slogan ?></h2> <?php endif ?>
        </div>
      </div>
    </div>
  <?php endif ?>

  <!-- primary and secondary menus -->
  <?php if($main_menu || $secondary_menu):?>
    <div class="row">

      <?php if ($main_menu) : ?>
        <div class="columns">
          <nav>
            <?php print theme('links__system_main_menu', array('links' => $main_menu, 'attributes' => array('id' => 'main-menu'))) ?>
          </nav>
        </div>
      <?php endif ?>

      <?php if ($secondary_menu) : ?>
        <div class="small-12 medium-6 large-6 columns">
          <nav>
            <?php print theme('links__system_secondary_menu', array('links' => $secondary_menu, 'attributes' => array('id' => 'secondary-menu'))) ?>
          </nav>
        </div>
      <?php endif ?>

    </div>
  <?php endif ?>

  <?php if (!empty($page['header'])) : ?>
    <div class="row">
      <div class="columns">
        <?php print render($page['header']) ?>
      </div>
    </div>
  <?php endif ?>

  <?php if (!empty($page['header__row_1'])) : ?>
    <div class="row-wrapper">
      <div class="row header--row-1">
        <?php print render($page['header__row_1']) ?>
      </div>
    </div>
  <?php endif ?>

  <?php if (!empty($page['header__row_2'])) : ?>
    <div class="row-wrapper">
      <div class="row header--row-2">
        <?php print render($page['header__row_2']) ?>
      </div>
    </div>
  <?php endif ?>

  <?php if (!empty($page['header__row_3'])) : ?>
    <div class="row-wrapper">
      <div class="row header--row-3">
        <?php print render($page['header__row_3']) ?>
      </div>
    </div>
  <?php endif ?>

</header>

<?php if ($breadcrumb): ?>
  <div id="breadcrumb">
    <div class="row">
      <div class="columns">
        <?php print $breadcrumb ?>
      </div>
    </div>
  </div> <!-- /.row -->
<?php endif ?>


<?php if ($messages) : ?>
  <div id="messages" class="row">
    <div class="columns">
      <?php print $messages ?>
    </div>
  </div> <!-- /.row -->
<?php endif ?>

<section class="content">

  <?php if(!empty($page['content_top__row_1'])) : ?>
    <div class="row-wrapper content-top--row-1">
      <div class="row">
        <?php print render($page['content_top__row_1']) ?>
      </div>
    </div>
  <?php endif ?>

  <?php if(!empty($page['content_top__row_2'])) : ?>
    <div class="row-wrapper content-top--row-2">
      <div class="row">
        <?php print render($page['content_top__row_2']) ?>
      </div>
    </div>
  <?php endif ?>

  <?php if(!empty($page['content_top__row_3'])) : ?>
    <div class="row-wrapper content-top--row-3">
      <div class="row">
        <?php print render($page['content_top__row_3']) ?>
      </div>
    </div>
  <?php endif ?>

  <div class="row">

    <?php // sidebars and content classes contains foundation classes generated by dynamic_sidebars plugins ?>
    <?php if ($page['sidebar_first']): ?>
      <aside id="sidebar-first" class="<?php if (isset($sidebar_first_classes)) print $sidebar_first_classes ?>">
        <?php print render($page['sidebar_first']) ?>
      </aside>
    <?php endif ?>

    <?php // sidebars and content classes contains foundation classes generated by dynamic_sidebars plugins ?>
    <div id="content" class="<?php if (isset($content_classes)) print $content_classes ?>">

      <a id="main-content"></a>
      <?php print render($title_prefix) ?>
      <?php if ($title): ?><h1 class="title" id="page-title"><?php print $title; ?></h1><?php endif ?>
      <?php print render($title_suffix) ?>
      <?php if ($tabs): ?><div class="tabs"><?php print render($tabs); ?></div><?php endif ?>
      <?php print render($page['help']) ?>
      <?php if ($action_links): ?><ul class="action-links"><?php print render($action_links) ?></ul><?php endif ?>

      <?php print render($page['content']) ?>


      <div class="row content-bottom--row-1">
        <?php if(isset($page['content_bottom__row_1'])) print render($page['content_bottom__row_1']) ?>
      </div>
      <div class="row content-bottom--row-2">
        <?php if(isset($page['content_bottom__row_2'])) print render($page['content_bottom__row_2']) ?>
      </div>
      <div class="row content-bottom--row-3">
        <?php if(isset($page['content_bottom__row_3'])) print render($page['content_bottom__row_3']) ?>
      </div>

      <?php print $feed_icons ?>

    </div>

    <?php if ($page['sidebar_second']): ?>
      <aside id="sidebar-second" class="<?php if (isset($sidebar_second_classes)) print $sidebar_second_classes ?>">
        <?php print render($page['sidebar_second']) ?>
      </aside>
    <?php endif; ?>

  </div> <!-- /.row -->
</section>

<?php if (!empty($page['footer']) || !empty($page['footer__row_1']) || !empty($page['footer__row_2']) || !empty($page['footer__row_3'])) : ?>
<footer>
<?php endif ?>

  <?php if(!empty($page['footer'])) : ?>
    <div class="row">
      <div class="columns">
        <?php print render($page['footer']); ?>
      </div>
    </div>
  <?php endif ?>

  <?php if(!empty($page['footer__row_1'])) : ?>
    <div class="row-wrapper footer--row-1">
      <div class="row">
        <?php print render($page['footer__row_1']); ?>
      </div>
    </div>
  <?php endif ?>

  <?php if(!empty($page['footer__row_2'])) : ?>
    <div class="row-wrapper footer--row-2">
      <div class="row">
        <?php print render($page['footer__row_2']); ?>
      </div>
    </div>
  <?php endif ?>

  <?php if(!empty($page['footer__row_3'])) : ?>
    <div class="row-wrapper footer--row-3">
      <div class="row">
        <?php print render($page['footer__row_3']); ?>
      </div>
    </div>
  <?php endif ?>

<?php if (!empty($page['footer']) || !empty($page['footer__row_1']) || !empty($page['footer__row_2']) || !empty($page['footer__row_3'])) : ?>
</footer>
<?php endif ?>
