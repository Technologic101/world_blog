<?php
require_once TEMPLATEPATH . '/libraries/envoy2014/core.php';
require_once TEMPLATEPATH . '/libraries/envoy2014/filters.php';
require_once TEMPLATEPATH . '/libraries/envoy2014/shortcodes.php';

// LESS processor, don't remove this unless you plan on using straight up CSS
require_once TEMPLATEPATH . '/libraries/envoy-less/bootstrap.php';

class Envoy2014
{
    /*
     * Initialize the bootstrap
     * 
     * @return void
     */
    public static function init()
    {
        self::initThemeSupport();
        self::initThemeFunctions();
        self::initFilters();
        self::initPostTypes();
        self::registerMenus();
        self::registerSidebars();
        self::registerStyles();
        self::registerScripts();
        self::registerShortcodes();
        self::initPlugins();
        self::initWidgets();
    }
    
    /**
     * Add theme support
     * 
     * @link http://codex.wordpress.org/Function_Reference/add_theme_support
     * 
     * @static
     * @return void
     */
    public static function initThemeSupport()
    {
        add_theme_support('nav-menus');
        add_theme_support('post-thumbnails');
        remove_action('wp_head', 'wp_generator');
        
        /* 
        envoy2014_set('default_layout', 'layout-default.php');
        envoy2014_set('default_post_layout', 'layout-default.php');

        envoy2014_disable_layout(array(
            'layout-clean.php',
            'layout-seo.php',
            'layout-sidebarboth.php',
            'layout-sidebarleft.php',
            'layout-sidebarright.php'
        )); */
       
       //add_image_size( $name, $width, $height, $crop );
    }
    
    /**
     * Add theme functions
     * 
     * @static
     * @return void
     */
    public static function initThemeFunctions()
    {
    }

    /**
     * Add any filters
     * 
     * @example add_filter('widget_text', 'do_shortcode');
     * 
     * @link http://codex.wordpress.org/Function_Reference/add_filter
     * @static
     * @return void
     */
    public static function initFilters()
    {
        //Add shortcodes to widgets
        add_filter('widget_text', 'do_shortcode');

        //Add count (first, second, third, etc.) to widgets for CSS purposes
        add_filter('dynamic_sidebar_params', 'envoy2014_add_widget_count');

        // Fix TinyMCE issues
        envoy2014_fixtinymce();
    }
    
    /**
     * Initialize post types
     * 
     * @link http://codex.wordpress.org/Function_Reference/register_post_type
     * 
     * @static
     * @return void
     */
    public static function initPostTypes() {}

    /**
     * Used to include() or require() 3rd party plugins for the theme
     * 
     * @example require TEMPLATEPATH . '/plugins/contact-form-7/wp-contact-form-7.php';
     * 
     * @static
     * @return void
     */
    public static function initPlugins() {}

    /**
     * Registers 3rd party widgets
     * 
     * @example add_action('widgets_init', create_function('', 'return register_widget("WP_Widget_Twitter_Pro");'));
     * 
     * @static
     * @return void
     */
    public static function initWidgets() {}
    
    /**
     * Register Wordpress menus (available in 3.0+)
     * 
     * @example register_nav_menu('main', 'Top Navigation Menu');
     * @example register_nav_menu('menu-bar', 'Front Page (Under Slider)');
     * 
     * @static
     * @return void
     */
    public static function registerMenus()
    {
        register_nav_menu('main', 'Top Navigation Menu');
        register_nav_menu('main-2', 'Top Navigation Part 2');
    }

    /**
     * Register any sidebars, including SEO top and bottom areas
     * 
     * @link http://codex.wordpress.org/Function_Reference/register_sidebar
     * 
     * @example 
     *
     * register_sidebar(array(
     *      'id' => 'envoy-seo-top',
     *      'name' => 'Top SEO Area',
     *      'before_widget' => '<div id="%1$s" class="widget %2$s">',
     *      'after_widget' => '</div>',
     *      'before_title' => '',
     *      'after_title' => ''
     *  ));
     * 
     * @static
     * @return void
     */
    public static function registerSidebars()
    {
        /**
         * PLEASE BE SURE TO PREFIX ALL ID'S WITH 'envoy-'
         */
        
        register_sidebar(array(
            'id' => 'envoy-header',
            'name' => 'Header',
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '',
            'after_title' => ''
        ));
        
        register_sidebar(array(
            'id' => 'envoy-sidebar-left',
            'name' => 'Left Sidebar',
            'before_widget' => '<aside id="%1$s" class="widget %2$s">',
            'after_widget' => '</aside>',
            'before_title' => '<h3>',
            'after_title' => '</h3>'
        ));
        
        register_sidebar(array(
            'id' => 'envoy-sidebar-right',
            'name' => 'Right Sidebar',
            'before_widget' => '<aside id="%1$s" class="widget %2$s">',
            'after_widget' => '</aside>',
            'before_title' => '<h3>',
            'after_title' => '</h3>'
        ));
        
        register_sidebar(array(
            'id' => 'envoy-footer',
            'name' => 'Footer',
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '',
            'after_title' => ''
        ));
    }
    
    /**
     * Register styles for use in header.php
     * 
     * @example wp_register_style($handle, $src, $dep, $ver, $media);\
     * @example wp_register_style('style', get_bloginfo('template_url') . '/css/style.css', array('reset'), '1.0', 'screen');
     * 
     * Use wp_enqueue_style($handle) in header.php
     * 
     * @static
     * @return void
     */
    public static function registerStyles()
    {
        wp_register_style('envoy-theme', get_bloginfo('template_url') . '/theme.less');
        wp_register_style('cesium', get_bloginfo('template_url') . '/js/cesium/build/cesium/widgets/widgets.css');

        // IE conditional styles
        global $wp_styles;
        foreach (array('9') as $_version) {
            wp_register_style('envoy-ie-lte-ie' . $_version, get_bloginfo('template_url') . '/css/ie/lte-ie' . $_version . '.less');
            $wp_styles->add_data('envoy-ie-lte-ie' . $_version, 'conditional', 'lte IE ' . $_version);
        }
    }

    /**
     * Register javascript for use in header.php
     * 
     * @link http://codex.wordpress.org/Function_Reference/wp_register_script
     * 
     * @example wp_register_script('cufon', get_bloginfo('template_url') . '/js/cufon/cufon-yui.js');
     * 
     * @static
     * @return void
     */
    public static function registerScripts()
    {
        $template_url = get_bloginfo('template_url');
        $url =  $template_url . '/js/';

        wp_register_script('envoy',                    $url . 'envoy/envoy.js', array('jquery', 'envoy-simplemodal'));
        wp_register_script('envoy-fluid',              $url . 'envoy/fluid.js');
        wp_register_script('envoy-global',             $url . 'global.js', array('jquery', 'cesium'));
        wp_register_script('envoy-mobile-nav',         $url . 'envoy/mobile-nav/mobile-nav.js');
        wp_register_script('envoy-mobile-bootstrap',   $url . 'envoy/mobile-nav/bootstrap.js');
        wp_register_script('envoy-touch-menu',         $url . 'envoy/touch-menu.js', array('jquery'));
        wp_register_script('envoy-bootstrap',          '//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js', array('jquery'));
        wp_register_script('cesium',                   $url . 'cesium/build/cesium/cesium.js');
    }

    /**
     * Register any Wordpress shortcodes to be used
     * 
     * @link http://codex.wordpress.org/Function_Reference/add_shortcode
     * @link http://codex.wordpress.org/Shortcode_API
     * 
     * @example add_shortcode('underline', 'shortcode_underline');
     * 
     * @static
     * @return void
     */
    public static function registerShortcodes()
    {
        add_shortcode('template_url', 'envoy2014_template_url');
        add_shortcode('url', 'envoy2014_url');
        add_shortcode('dynamic_sidebar', 'envoy2014_dynamic_sidebar');
    }
}
