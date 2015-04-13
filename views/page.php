<?php 
/**
 * Single Page View
 *
 * Location: #content
 */
?>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    <?php echo envoy2014_render('snippets/body/content/breadcrumbs.php'); ?>
    <?php echo envoy2014_render('snippets/body/content/page.php'); ?>
<?php endwhile; endif; ?>
