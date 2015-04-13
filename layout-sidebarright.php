<?php /* Template Name: Right Sidebar */ ?>
<?php /* WARNING: DO NOT CHANGE THIS FILE! */ ?>
<?php $content_for_layout = envoy2014_render('snippets/body/content.php'); ?>
<?php get_header(); ?>
<div id="body">
    <?php echo envoy2014_render('snippets/body/start.php'); ?>
    <main id="content">
        <?php echo $content_for_layout; ?>
    </main><!-- #content -->
    <div id="sidebar-right" class="sidebar">
        <?php echo envoy2014_render('snippets/body/sidebar-right.php'); ?>
    </div><!-- #sidebar-right -->
    <?php echo envoy2014_render('snippets/body/end.php'); ?>
</div><!-- #body -->
<?php get_footer(); ?>
