<?php

/**
 * Add count to widgets
 * 
 * @return [type] [description]
 */
function envoy2014_add_widget_count($args) {
    static $sidebars;

    static $locations = array(
        1 => 'first',
        2 => 'second',
        3 => 'third',
        4 => 'fourth',
        5 => 'fifth',
        6 => 'sixth',
        7 => 'seventh',
        8 => 'eighth',
        9 => 'ninth',
        10 => 'tenth'
    );

    if (!isset($sidebars[$args[0]['id']])) {
        $sidebars[$args[0]['id']] = 0;
    }

    $sidebars[$args[0]['id']]++;

    if (isset($locations[$sidebars[$args[0]['id']]])) {
        $args[0]['before_widget'] = preg_replace('/widget\s/', 'widget '.$locations[$sidebars[$args[0]['id']]].' ', $args[0]['before_widget']);
    }

    return $args;
}
