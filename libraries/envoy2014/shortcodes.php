<?php

function envoy2014_dynamic_sidebar($atts)
{
    $a = shortcode_atts( array(
        'id' => null
    ), $atts );

    if (!is_null($a['id']) && is_active_sidebar($a['id'])) {
        ob_start();
        dynamic_sidebar($a['id']);
        return ob_get_clean();
    }
    
    return '';
}

function envoy2014_template_url($atts)
{
    return get_bloginfo('template_url');
}

function envoy2014_url($atts)
{
    return get_bloginfo('url');
}
