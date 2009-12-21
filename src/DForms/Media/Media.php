<?php

/**
 * A media property with special methods for handling media.
 *
 * .. note:: Some of these method names are not camel case. They should be,
 *    but it is *much* easier to keep all the names lowercase for now.
 */
class DForms_Media_Media
{
    private $_media_types;
    private $_css;
    private $_js;
    
    public function __construct($media=null) {
        if (is_null($media)) {
            $media = array();
        }
        
        $this->_media_types = array('css', 'js');
        
        $this->_css = array();
        $this->_js = array();
        
        foreach ($this->_media_types as $type) {
            if (array_key_exists($type, $media)) {
                call_user_func(array($this, 'add_' . $type), $media[$type]);
            }
        }
    }
    
    /**
     * @todo Make this throw an exception if invalid type is supplied.
     */
    public function __get($name) {
        if (in_array($name, $this->_media_types)) {
            $media = array();
            $media_name = '_' . $name;
            $media[$name] = $this->$media_name;
            return new DForms_Media_Media($media);
        }
    }
    
    public function __toString() {
        return $this->render();
    }
    
    public function render() {
        $lines = array();
        foreach ($this->_media_types as $type) {
            $line = call_user_func(array($this, 'render_' . $type));
            if ($line != '') {
                $lines[] = $line;
            }
        }
        return implode("\n", $lines);
    }
    
    public function render_js() {
        $lines = array();
        foreach ($this->_js as $src) {
            $lines[] = sprintf(
                '<script type="text/javascript" src="%s"></script>',
                $src
            );
        }
        return implode("\n", $lines);
    }
    
    public function render_css() {
        $lines = array();
        $media = array_keys($this->_css);
        sort($media);
        foreach ($media as $medium) {
            foreach($this->_css[$medium] as $src) {
                $lines[] = sprintf(
                    '<link href="%s" type="text/css" media="%s" rel="stylesheet" />',
                    $src,
                    $medium
                );
            }
        }
        return implode("\n", $lines);
    }
    
    public function add_js($data=null) {
        if (!is_null($data)) {
            $this->_js = array_merge($this->_js, $data);
        }
    }
    
    public function add_css($data) {
        if (!is_null($data)) {
            foreach ($data as $medium => $paths) {
                if (array_key_exists($medium, $this->_css)) {
                    $this->_css[$medium] = array_merge(
                        $this->_css[$medium],
                        $data[$medium]
                    );
                } else {
                    $this->_css[$medium] = $paths;
                }
            }
        }
    }
    
    public function add($data) {
        foreach ($this->_media_types as $type) {
            if (array_key_exists($type, $data)) {
                call_user_func(array($this, 'add_' . $type), $data[$type]);
            }
        }
    }
    
    public function mergeMedia($media) {
        $combined = new DForms_Media_Media();
        foreach ($this->_media_types as $type) {
            $type_name = '_' . $type;
            call_user_func(
                array($combined, 'add_' . $type),
                $this->$type_name
            );
            call_user_func(
                array($combined, 'add_' . $type),
                $media->$type_name
            );
        }
        return $combined;
    }
}