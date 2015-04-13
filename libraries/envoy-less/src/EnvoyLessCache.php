<?php
require_once 'EnvoyLess.php';


class EnvoyLessCache
{
    /**
     * @var Less_Parser
     */
    protected $parser;

    /**
     * @var string
     */
    protected $cache_dir;

    /**
     * @var string
     */
    protected $prefix;

    /**
     * @var bool
     */
    protected $clean = false;

    /**
     * @param string $cache_dir
     * @param string $prefix
     */
    public function __construct(Less_Parser $parser, $cache_dir, $prefix = 'less-')
    {
        $this->parser = $parser;
        $this->cache_dir = $this->cleanupDirName((string) $cache_dir);
        $this->prefix = $this->cleanupPrefix((string) $prefix);
    }

    /**
     * @param array $less_files
     * @param array $parser_options
     * @param bool  $use_cache
     *
     * @return string
     */
    public function get($less_files, $use_cache = true)
    {
        $less_files = (array) $less_files;

        $this->ensureCacheDirExists();
        $this->ensureCacheDirIsWritable();

        $list_name = $this->generateListName($less_files);

        if (true === $use_cache) {
            $list = $this->maybeLoadListFile($list_name);

            if ($list) {
                $compiled_name = $this->generateCssName($list);
                $loaded = $this->maybeLoadCssFile($compiled_name);

                if (false !== $loaded) {
                    return $loaded;
                }
            }
        }
        
        $compiled = $this->compile($less_files);
        
        if (false === $compiled) {
            return false;
        }

        $file_list = $this->parser->AllParsedFiles();
        $compiled_name = $this->generateCssName($file_list);

        $this->writeFile($list_name, implode("\n", $file_list));
        $this->writeFile($compiled_name, $compiled);

        $this->clean();

        return $compiled_name;
    }

    /**
     * @param array $less_files
     * @param array $parser_options
     * 
     * @return string
     */
    public function compile(&$less_files)
    {
        // combine files
        foreach ($less_files as $file_path => $uri_or_less) {

            //treat as less markup if there are newline characters
            if (false !== strpos($uri_or_less, "\n")) {
                $this->parser->Parse($uri_or_less);
                continue;
            }

            $this->parser->ParseFile($file_path, $uri_or_less);
        }

        $compiled = $this->parser->getCss();
        return $compiled;
    }

    /**
     * @return void
     */
    public function clean()
    {
        if ($this->clean) {
            return;
        }

        $files = scandir($this->cache_dir);
        
        if ($files) {
            $check_time = time() - 604800;
            
            foreach ($files as $file) {
                if (0 !== strpos($file, $this->prefix)) {
                    continue;
                }
                
                $full_path = $this->cache_dir . $file;
                
                if (filemtime($full_path) > $check_time) {
                    continue;
                }

                unlink($full_path);
            }
        }

        $this->clean = true;
    }

    /**
     * @param string $list_name
     * 
     * @return array
     */
    public function maybeLoadListFile($list_name)
    {
        $list_file = $this->cache_dir . $list_name;

        if (false == file_exists($list_file)) {
            return false;
        }

        $list = explode("\n", file_get_contents($list_file));
        @touch($list_file);

        return $list;
    }

    /**
     * @param array $less_files
     * 
     * @return string
     */
    public function maybeLoadCssFile($compiled_name)
    {
        $compiled_file = $this->cache_dir . $compiled_name;

        if (false === file_exists($compiled_file)) {
            return false;
        }

        @touch($compiled_file);

        return $compiled_name;
    }

    /**
     * @param string filename
     * @param string $content
     * 
     * @return void
     */
    protected function writeFile($filename, $content)
    {
        file_put_contents($this->cache_dir . $filename, $content);
    }

    /**
     * @param array $files
     * 
     * @return string
     */
    protected function generateCssName($files)
    {
        $temp = array(EnvoyLess::VERSION);
        
        foreach ($files as $file) {
            if (file_exists($file)) {
                $temp[] = filemtime($file) . filesize($file) . $file;
            }
        }

        return $this->prefix . sha1(json_encode($temp)) . '.css';
    }

    /**
     * @param  array $less_files
     * 
     * @return string
     */
    protected function generateListName($less_files)
    {
        $less_files = (array) $less_files;
        $hash = md5(json_encode($less_files));
        $list_file = $this->prefix . $hash . '.list';

        return $list_file;
    }

    /**
     * @param  string $dir
     * 
     * @throws RuntimeException
     * @return void
     */
    protected function ensureCacheDirExists()
    {
        if (!file_exists($this->cache_dir)) {
            if (!mkdir($this->cache_dir)) {
                throw new RuntimeException(sprintf('Unable to create directory %s from %s', $this->cache_dir, get_class($this)));
            }
        } else if(!is_dir($this->cache_dir)) {
            throw new RuntimeException(sprintf('Directory does not exist: %s', $this->cache_dir));
        }
    }

    /**
     * @param  string $dir
     * 
     * @throws RuntimeException
     * @return void
     */
    protected function ensureCacheDirIsWritable()
    {
        if (!is_writable($this->cache_dir)) {
            throw new RuntimeException(sprintf('Directory is not writable: %s', $this->cache_dir));
        }
    }

    /**
     * @param  string $prefix
     *
     * @return string
     */
    protected function cleanupPrefix($prefix)
    {
        return preg_replace('[^A-Za-z0-9\-\_]', '', $prefix);
    }

    /**
     * @param  string $dir
     * 
     * @return string
     */
    protected function cleanupDirName($dir)
    {
        $dir = str_replace('\\','/', $dir);
        $dir = rtrim($dir , '/') . '/';

        return $dir;
    }
}
