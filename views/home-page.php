<?php 
/**
 * Home Page View for a Static Page. 
 *
 * Create a page in the Wordpress backend and make sure it has 
 * the "Home Page" template selected, then go to Settings > Reading 
 * and set "Front page displays" to "A static page" and select the 
 * page you created under "Front page"
 *
 * Remove the comments wrapped around the sidebar html to add 
 * sidebars to this view
 *
 * Location: html > #body
 */
?>
<?php /*
<div id="sidebar-left" class="sidebar">
    <?php echo envoy2014_render('snippets/body/sidebar-left.php'); ?>
</div><!-- #sidebar-left -->
*/ ?>
<main id="content">
    <?php echo envoy2014_render('snippets/body/content/home-loop.php'); ?>
</main><!-- #content -->
<aside id="loading">Loading...</aside>
<?php /*
<div id="sidebar-right" class="sidebar">
    <?php echo envoy2014_render('snippets/body/sidebar-right.php'); ?>
</div><!-- #sidebar-right -->
 */ ?>
