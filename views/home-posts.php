<?php
/**
 * Home Page View for Posts. 
 * 
 * Create a page in the Wordpress backend and make sure it has the 
 * "Home Page" template selected, then go to Settings > Reading 
 * and set "Front page displays" to "A static page" and select 
 * the page you created under "Posts page"
 *
 * Remove the comments wrapped around the sidebar html to add 
 * sidebars to this view
 *
 * Query example:
 *
 * <?php query_posts("showposts=1&cat=".get_cat_ID('Category')); ?>
 * <?php while (have_posts()) : the_post(); ?>
 *      <?php the_content(); ?>
 * <?php endwhile; wp_reset_query(); ?>
 * 
 * Location: #body-wrapper
 */
?>
<?php /*
<div id="sidebar-left" class="sidebar">
    <?php echo envoy2014_render('snippets/body/sidebar-left.php'); ?>
</div><!-- #sidebar-left -->
*/ ?>
<main id="content">
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <?php echo envoy2014_render('snippets/body/content/excerpt.php'); ?>
    <?php endwhile; endif; ?>
    <?php echo envoy2014_render('snippets/body/content/post-pager.php'); ?>
</main>
<?php /*
<div id="sidebar-right" class="sidebar">
    <?php echo envoy2014_render('snippets/body/sidebar-right.php'); ?>
</div><!-- #sidebar-right -->
 */ ?>
