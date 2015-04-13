<?php
$envoy2014_settings = array(
    'body_class' => '',
    'default_layout' => 'layout-default.php',
    'default_post_layout' => 'layout-default.php'
);

/**
 * Initialize framework, should go in index.php
 * 
 * @return void
 */
function envoy2014_init() {
    if (is_home() || is_front_page()) {
        require TEMPLATEPATH . '/layout-homepage.php';
    } else if (is_single()) {
        require TEMPLATEPATH . '/' . envoy2014_get('default_post_layout', 'layout-default.php');
    } else {
        require TEMPLATEPATH . '/' . envoy2014_get('default_layout', 'layout-default.php');
    }
}

/**
 * Get a setting, returns $default if key not found
 *
 * @param string $key
 * @param string $default
 * @return string
 */
function envoy2014_get($key, $default = '') {
    global $envoy2014_settings;

    if (array_key_exists($key, (array) $envoy2014_settings)) {
        return $envoy2014_settings[$key];
    }

    return $default;
}

/**
 * Set a setting if the key exists
 *
 * @param string $key
 * @param string $value
 * @return string
 */
function envoy2014_set($key, $value) {
    global $envoy2014_settings;

    if (array_key_exists($key, (array) $envoy2014_settings)) {
        $envoy2014_settings[$key] = (string) $value;
    }
}


/**
 * Render a template, base is TEMPLATEPATH
 * 
 * @param  string  $template
 * @param  array   $vars
 * @param  boolean $include_globals
 * @return string
 */
function envoy2014_render($template, $vars = array(), $include_globals = true) {
    extract($vars);
    
    if ($include_globals) {
        global $posts, $post, $wp_did_header, $wp_query, $wp_rewrite, $wpdb, $wp_version, $wp, $id, $comment, $user_ID;

        if (is_array($wp_query->query_vars)) {
            extract($wp_query->query_vars, EXTR_SKIP);
        }
    }
     
    ob_start();
    require(TEMPLATEPATH . '/' . $template);
    $applied_template = ob_get_contents();
    ob_end_clean();
     
    return $applied_template;
}

/**
 * Render a view
 * 
 * @param  boolean $return
 * @return string if boolean is true
 */
function envoy2014_render_view($return = false) {
    $view = 'page.php';

    if (is_home() || is_front_page()):
        if (is_page()):
            $view = 'home-page.php';
        else:
            $view = 'home-posts.php';
        endif;   
    elseif (is_single()):
        $view = 'single.php';
    elseif (is_category()):
        $view = 'category.php';
    elseif (is_search()):
        $view = 'search.php';
    elseif (is_page()):
        $view = 'page.php';
    elseif (is_archive()):
        if (is_tag()):
            $view = 'tag.php';
        else:
            $view = 'archive.php';
        endif;
    endif;

    $loc = 'views/' . $view;
    
    if ($return == true) {
        return envoy2014_render($loc);
    }

    echo envoy2014_render($loc);
}

/**
 * Disable a layout from showing up in a list
 * 
 * @param  array  $files_to_delete
 * @return void
 */
function envoy2014_disable_layout($files_to_delete = array()) {
    global $wp_themes;

    // As convenience, allow a single value to be used as a scalar without wrapping it in a useless array()
    if (is_scalar($files_to_delete)) {
        $files_to_delete = array($files_to_delete);
    }

    // remove TLA if it was provided
    $files_to_delete = preg_replace("/\.[^.]+$/", '', $files_to_delete);

    // Populate the global $wp_themes array
    get_themes();
    $current_theme_name = get_current_theme();

    // Note that we're taking a reference to $wp_themes so we can modify it in-place
    $template_files = &$wp_themes[$current_theme_name]['Template Files'];

    foreach ($template_files as $file_path) {
        foreach ($files_to_delete as $file_name) {
            if (preg_match('/\/' . $file_name . '\.[^.]+$/', $file_path)) {
                if ($key = array_search($file_path, $template_files)) {
                    unset($template_files[$key]);
                }
            }
        }
    }
}

/**
 * Fixes for TinyMCE
 * 
 * @return void
 */
function envoy2014_fixtinymce() {
    function on_tiny_mce_before_init($init) {
        $init['apply_source_formatting'] = true;
        $init['forced_root_block'] = false;
        $init['force_p_newlines'] = false;
        return $init;
    }

    add_filter('tiny_mce_before_init', 'on_tiny_mce_before_init');

    function on_after_wp_tiny_mce() {
        ?>
        <script type="text/javascript">
            if ( typeof(jQuery) != 'undefined' ) {
              jQuery('body').bind('afterPreWpautop', function(e, o){
                o.data = o.unfiltered
                .replace(/caption\]\[caption/g, 'caption] [caption')
                .replace(/<object[\s\S]+?<\/object>/g, function(a) {
                  return a.replace(/[\r\n]+/g, ' ');
                });
              }).bind('afterWpautop', function(e, o){
                o.data = o.unfiltered;
              });
            }
        </script>
        <?php
    }

    add_action('after_wp_tiny_mce', 'on_after_wp_tiny_mce');
    remove_filter('the_content', 'wpautop');
}

/**
 * Breadcrumbs function
 *
 * @param string $delimiter
 * @param array $before
 * @return string
 */
function envoy2014_breadcrumbs($delimiter = ' &raquo; ', array $before = array()) {
    global $post;
    $title = the_title('','', FALSE);
    $ancestors = array_reverse(get_post_ancestors($post->ID));
    array_push($ancestors, $post->ID);

    $output = sprintf('<a href="%s">Home</a>%s', get_bloginfo('url'), $delimiter);

    foreach ($before as $_text => $_link) {
        $output .= sprintf('<a href="%s">%s</a>%s', strip_tags($_link), $_text, $delimiter);
    }

    foreach ($ancestors as $ancestor){
        if($ancestor != end($ancestors)){
            $output .= sprintf('<a href="%s">%s</a>%s', get_permalink($ancestor), strip_tags(apply_filters('single_post_title', get_the_title($ancestor))), $delimiter);
        }else{
            $output .= strip_tags(apply_filters('single_post_title', get_the_title($ancestor)));
        }
    }

    return $output;
}
