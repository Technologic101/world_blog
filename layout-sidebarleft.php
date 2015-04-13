<?php /* Template Name: Left Sidebar */ ?>
<?php /* WARNING: DO NOT CHANGE THIS FILE! */ ?>
<?php $content_for_layout = envoy2014_render('snippets/body/content.php'); ?>
<?php get_header(); ?>
<div id="body">
    <?php echo envoy2014_render('snippets/body/start.php'); ?>
    <div id="sidebar-left" class="sidebar">
        <?php echo envoy2014_render('snippets/body/sidebar-left.php'); ?>
    </div><!-- #sidebar-left -->
    <main id="content">
        <?php echo $content_for_layout; ?>
    </main><!-- #content -->
    <?php echo envoy2014_render('snippets/body/end.php'); ?>
</div><!-- #body -->
<?php get_footer(); ?>
