<?php /* NOTE: Don't for get to call wp_head(); at some point in this file! */ ?>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php wp_title(''); ?></title>
<!--[if lt IE 9]>
    <script src="//oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="//oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->

<?php /* Styles */ ?>
<?php wp_enqueue_style('envoy-theme'); ?>
<?php wp_enqueue_style('envoy-ie-lte-ie9'); ?>
<?php wp_enqueue_style('cesium'); ?>

<?php /* Scripts */ ?>
<?php wp_enqueue_script('jquery'); ?>
<?php wp_enqueue_script('cesium'); ?>
<?php wp_enqueue_script('envoy-fluid'); ?>
<?php wp_enqueue_script('envoy-global'); ?>

<?php wp_head(); ?>

<?php /* RENDERS IN FOOTER */ ?>
<?php //wp_enqueue_script('envoy-mobile-nav'); ?>
<?php //wp_enqueue_script('envoy-mobile-bootstrap'); ?>
<?php //wp_enqueue_script('envoy-touch-menu'); ?>
