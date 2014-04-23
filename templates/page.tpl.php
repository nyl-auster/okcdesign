<?php
/**
 * @file page.tpl.php
 * @see modules/system/page.tpl.php for drupal documentation of a page.tpl.php
 * Only things specific to okcdesign are commented here.
 */
?>

<?php if  (okcdesign_plugin_is_enabled('foundation_topbar')) :?>

  <?php
  print theme('foundation_topbar', array(
    'links_left' => menu_tree_output(menu_tree_all_data(variable_get('menu_primary_links_source', 'main-menu'))),
    'links_right' => menu_tree_output(menu_tree_all_data(variable_get('menu_primary_links_source', 'user-menu')))
  ));
  ?>
<?php endif ?>

  <div class="row-wrapper" id="header-top" >
    <div class="row">

      <!-- site name , logo & slogan -->
      <div id="site-informations">

        <?php if ($site_name || $logo) : ?>
          <h1>

            <?php if ($logo): ?>
              <a href="<?php print $front_page ?>" title="<?php print t('Home') ?>" rel="home" id="logo">
                <img src="<?php print $logo; ?>" alt="<?php print t('Home') ?>" />
              </a>
            <?php endif ?>

            <a href="<?php print $front_page; ?>" title="<?php print t('Home'); ?>" rel="home" id="logo">
              <?php print $site_name ?>
            </a>

            <?php if ($site_slogan): ?> <small><?php print $site_slogan ?></small> <?php endif ?>

          </h1>
        <?php endif ?>


      </div> <!-- /#site-informations -->

      <?php if($main_menu):?>
        <nav>
          <?php print theme('links__system_main_menu', array('links' => $main_menu, 'attributes' => array('id' => 'main-menu'))) ?>
        </nav>
      <?php endif ?>

      <?php if ($main_menu || $secondary_menu): ?>
        <nav class="">
          <?php print theme('links__system_secondary_menu', array('links' => $secondary_menu, 'attributes' => array('id' => 'secondary-menu'))) ?>
        </nav>
      <?php endif; ?>


      <!-- / menus -->


    </div> <!-- /.row -->
  </div> <!-- /.row-wrapper-->


<?php if($page['header']) : ?>
  <header class="row-wrapper">
    <div class="row">
      <div class="small-12 columns">
        <?php print render($page['header']) ?>
      </div>
    </div> <!-- /.row -->
  </header> <!-- /.row-wrapper -->
<?php endif ?>

<?php if ($breadcrumb): ?>
  <div class ="row-wrapper" id="breadcrumb">
    <div class="row">
      <div class="small-12 columns">
        <?php print $breadcrumb ?>
      </div>
    </div> <!-- /.row -->
  </div> <!-- /.row-wrapper -->
<?php endif ?>

<?php if ($messages) : ?>
  <div class="row-wrapper" id="messages">
    <div class="row">
      <div class="small-12 columns">
        <?php print $messages ?>
      </div>
    </div> <!-- /.row -->
  </div>  <!-- /.row-wrapper -->
<?php endif ?>


  <section id="section-content" class="row-wrapper">
    <div class="row">

      <?php if ($page['sidebar_first']): ?>
        <aside id="sidebar-first" class="small-12 <?php print $sidebar_first_grid_classes ?>">
          <?php print render($page['sidebar_first']) ?>
        </aside>
      <?php endif ?>

      <div id="content" class="small-12 <?php print $content_grid_classes ?>">


        <?php if ($page['highlighted']): ?><div id="highlighted"><?php print render($page['highlighted']) ?></div><?php endif ?>
        <a id="main-content"></a>
        <?php print render($title_prefix) ?>
        <?php if ($title): ?><h1 class="title" id="page-title"><?php print $title; ?></h1><?php endif ?>
        <?php print render($title_suffix) ?>
        <?php if ($tabs): ?><div class="tabs"><?php print render($tabs); ?></div><?php endif ?>
        <?php print render($page['help']) ?>
        <?php if ($action_links): ?><ul class="action-links"><?php print render($action_links) ?></ul><?php endif ?>
        <?php print render($page['content']) ?>
        <?php print $feed_icons ?>
      </div>

      <?php if ($page['sidebar_second']): ?>
        <aside id="sidebar-second" class="small-12 <?php print $sidebar_second_grid_classes ?>">
          <?php print render($page['sidebar_second']) ?>
        </aside>
      <?php endif; ?>

    </div>
  </section>

<?php if ($page['footer']) : ?>
  <footer class="row-wrapper">
    <div class="row">
      <?php print render($page['footer']); ?>
      <div>  <!-- /.row -->
  </footer>  <!-- /.row-wrapper -->
<?php endif ?>