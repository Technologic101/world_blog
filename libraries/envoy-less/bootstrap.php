<?php
require_once dirname(__FILE__) . '/src/EnvoyLess.php';

$parser = new Less_Parser(array('compress' => true));

if (!isset($wp_styles) || false === ($wp_styles instanceof WP_Styles)) {
    $wp_styles = new WP_Styles();
}

$wp_upload_dir = wp_upload_dir();
$upload_dir = $wp_upload_dir['basedir'] . '/envoy-less/';
$upload_uri = $wp_upload_dir['baseurl'] . '/envoy-less/';
$cache = new EnvoyLessCache($parser, $upload_dir, 'envoy-');
$always_recompile = (defined('WP_DEBUG') && WP_DEBUG);

$envoy_less = new EnvoyLess($wp_styles, $cache, $upload_dir, $upload_uri, $always_recompile);
$envoy_less->register();

add_action('after_setup_theme', array($envoy_less, 'install'));
