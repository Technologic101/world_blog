<?php

class EnvoyLessStylesheet
{
    protected $stylesheet;

    protected $source_path;

    protected $source_uri;

    public function __construct(_WP_Dependency $stylesheet)
    {
        $this->stylesheet = $stylesheet;
        $this->stylesheet->ver = null;
    }

    public function getStylesheet()
    {
        return $this->stylesheet;
    }

    public function getSourcePath()
    {
        if (null === $this->source_path) {
            $this->source_path = WP_CONTENT_DIR . preg_replace('#^' . content_url() . '#U', '', $this->getSourceUri());
        }
        
        return $this->source_path;
    }

    public function getSourceBaseUri()
    {
        return trailingslashit(dirname($this->getSourceUri()));
    }

    public function getSourceUri()
    {
        return $this->stylesheet->src;
    }
}
