<?php
require_once dirname(__FILE__) . '/../libraries/lessphp/Less.php';
require_once 'EnvoyLessCache.php';
require_once 'EnvoyLessStylesheet.php';


class EnvoyLess
{
    const MATCH_PATTERN = '/\.less$/U';

    const VERSION = 1;

    /**
     * @var WP_Styles
     */
    protected $wp_styles;

    /**
     * @var EnvoyLessCache
     */
    protected $cache;

    /**
     * @var string
     */
    protected $upload_dir;

    /**
     * @var string
     */
    protected $upload_uri;

    /**
     * @var boolean
     */
    protected $always_recompile = false;

    /**
     * @param WP_Styles   $wp_styles
     * @param Less_Parser $parser
     * @param string $upload_dir
     * @param string $upload_uri
     * @param boolean $always_recompile
     */
    public function __construct(WP_Styles $wp_styles, EnvoyLessCache $cache, $upload_dir, $upload_uri, $always_recompile = false)
    {
        $this->wp_styles = $wp_styles;
        $this->cache = $cache;
        $this->upload_dir = trailingslashit($upload_dir);
        $this->upload_uri = trailingslashit($upload_uri);
        $this->always_recompile = $always_recompile;
    }

    /**
     * @return WP_Styles
     */
    public function getWpStyles()
    {
        return $this->wp_styles;
    }

    /**
     * @return string
     */
    public function getUploadDir()
    {
        return $this->upload_dir;
    }

    /**
     * @return string
     */
    public function getUploadUri()
    {
        return $this->upload_uri;
    }

    /**
     * @return void
     */
    public function init()
    {
        add_action('wp_enqueue_scripts', array($this, 'processStylesheets'), 999, 0);
    }

    /**
     * @return void
     */
    public function register()
    {
        add_action('init', array($this, 'init'));
        add_action('envoy-less-clear-cache', array('Less_Cache', 'CleanCache'));
    }

    /**
     * @return void
     */
    public function install()
    {
        if (false === wp_get_schedule('envoy-less-clear-cache')) {
            wp_schedule_event(time(), 'daily', 'envoy-less-clear-cache');
        }

        wp_clear_scheduled_hook('envoy-less-clear-cache');
    }

    /**
     * @return void
     */
    public function uninstall()
    {
        wp_clear_scheduled_hook('envoy-less-clear-cache');
    }

    /**
     * @param  boolean $force
     * @return void
     */
    public function processStylesheets($force = false)
    {
        $force = is_bool($force) && $force ? !!$force : false;
        $to_process = array();

        foreach ((array) $this->wp_styles->queue as $style_id) {
            if (preg_match(self::MATCH_PATTERN, $this->wp_styles->registered[$style_id]->src)) {
                $to_process[] = $style_id;
            }
        }

        if (empty($to_process)) {
            return;
        }

        if (!wp_mkdir_p($this->upload_dir)) {
            throw new RuntimeException(sprintf('The upload dir folder (\'%s\') is not writable from %s.', $this->upload_dir, get_class($this)));
        }

        foreach ($to_process as $style_id) {
            $this->processStylesheet($style_id, $force);
        }
    }

    /**
     * @param  string  $handle
     * @param  boolean $force
     * @return void
     */
    public function processStylesheet($handle, $force = false)
    {
        $force = !!$force ? $force : $this->always_recompile;
        $stylesheet = new EnvoyLessStylesheet($this->wp_styles->registered[$handle]);
        $this->wp_styles->registered[$handle]->src = $this->cache($stylesheet, $force);
    }

    /**
     * @param  EnvoyLessStylesheet $stylesheet
     * @param  boolean             $force
     * @return string
     */
    public function cache(EnvoyLessStylesheet $stylesheet, $force = false)
    {
        $to_cache = array($stylesheet->getSourcePath() => $stylesheet->getSourceBaseUri());
        $use_cache = false === $force;
        $cached_filename = $this->cache->get($to_cache, $use_cache);

        return $this->upload_uri . $cached_filename;
    }

}
