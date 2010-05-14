<?php
/**
 * Media
 *
 * This file defines the media handling class.
 *
 * PHP version 5
 *
 * LICENSE: This source file is subject to version 3.0 of the Creative 
 * Commons Attribution-Share Alike United States license that is available 
 * through the world-wide-web at the following URI: 
 * http://creativecommons.org/licenses/by-nc-nd/3.0/us/. If you did 
 * not receive a copy of the license and are unable to obtain it through
 * the web, please send a note to the author and a copy will be provided
 * for you.
 *
 * @category   HTML
 * @package    DForms
 * @subpackage Media
 * @author     Greg Thornton <xdissent@gmail.com>
 * @copyright  2009 Greg Thornton
 * @license    http://creativecommons.org/licenses/by-sa/3.0/us/
 * @link       http://xdissent.github.com/dforms/
 */
 
namespace DForms\Media;

/**
 * A media property with special methods for handling media.
 *
 * Note: Some of these method names are not camel case. They should be,
 * but it is *much* easier to keep all the names lowercase for now.
 *
 * @category   HTML
 * @package    DForms
 * @subpackage Media
 * @author     Greg Thornton <xdissent@gmail.com>
 * @copyright  2009 Greg Thornton
 * @license    http://creativecommons.org/licenses/by-sa/3.0/us/
 * @link       http://xdissent.github.com/dforms/
 */
class Media
{
    /**
     * Available media types ("js" and "css" by default)
     *
     * @var array
     */
    private $_media_types;
    
    /**
     * CSS media associated with a media object.
     *
     * @var array
     */
    private $_css;
    
    /**
     * JS media associated with a media object.
     *
     * @var array
     */
    private $_js;
    
    /**
     * Instantiate a media object.
     *
     * @param array $media The media array to copy into the media object.
     *
     * @return null
     */
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
     * Returns a new media type with a subset of the current media.
     *
     * This magic method is useful for accessing one particular media type from
     * a media object instance:
     *
     * <code>
     * // Create a media object with javascript and stylesheets.
     * $media = new Media(array(
     *     'js' => array('test.js'),
     *     'css' => array('screen' => array('test.css'))
     * );
     *
     * // Extract a new media object with only javascript elements.
     * $js_media = $media->js;
     * </code>
     *
     * @param string $name The name of the media type to retrieve.
     * 
     * @return object
     * @todo Refactor media access to use array access rather than properties.
     */
    public function __get($name) {
        if (in_array($name, $this->_media_types)) {
            $media = array();
            $media_name = '_' . $name;
            $media[$name] = $this->$media_name;
            return new Media($media);
        }
    }
    
    /**
     * Returns the media definitions rendered as HTML by default.
     *
     * @return string
     */
    public function __toString() {
        return $this->render();
    }
    
    /**
     * Returns all media definitions rendered as HTML.
     *
     * @return string
     */
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
    
    /**
     * Returns only javascript media definitions rendered as HTML.
     *
     * @return string
     */
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
    
    /**
     * Returns only CSS media definitions rendered as HTML.
     *
     * @return string
     */
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
    
    /**
     * Adds an array of javascript media to the media object.
     *
     * @param array $data The javascript media array to add.
     *
     * @return null
     */
    public function add_js($data=null) {
        if (!is_null($data)) {
            $this->_js = array_merge($this->_js, $data);
        }
    }
    
    /**
     * Adds an array of CSS media to the media object.
     *
     * @param array $data The CSS media array to add.
     *
     * @return null
     */
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
    
    /**
     * Adds an array of media to the media object.
     *
     * @param array $data The media array to add.
     *
     * @return null
     */
    public function add($data) {
        foreach ($this->_media_types as $type) {
            if (array_key_exists($type, $data)) {
                call_user_func(array($this, 'add_' . $type), $data[$type]);
            }
        }
    }
    
    /**
     * Merges one media object with another and returns the combined media.
     *
     * @param array $data The media object to merge with.
     *
     * @return object
     */
    public function mergeMedia($media) {
        $combined = new Media();
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