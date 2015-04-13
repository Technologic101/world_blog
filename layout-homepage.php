<?php /* Template Name: Home Page */ ?>
<?php /* WARNING: DO NOT CHANGE THIS FILE! */ ?>
<?php $content_for_layout = envoy2014_render('snippets/body/content.php'); ?>
<?php get_header(); ?>
<div id="body">
    <?php echo envoy2014_render('snippets/body/start.php'); ?>
    <?php echo $content_for_layout; ?>
    <?php echo envoy2014_render('snippets/body/end.php'); ?>
</div><!-- #body -->
<?php get_footer(); ?>
