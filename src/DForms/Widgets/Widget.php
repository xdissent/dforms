<?php
/**
 * Widget
 *
 * This file defines an auto loader class that handles the loading of DForms 
 * classes in PHP scripts.
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
 * @subpackage Widgets
 * @author     Greg Thornton <xdissent@gmail.com>
 * @copyright  2009 Greg Thornton
 * @license    http://creativecommons.org/licenses/by-sa/3.0/us/
 * @link       http://xdissent.github.com/dforms/
 */

/**
 * The DForms widget base class.
 *
 * @category   HTML
 * @package    DForms
 * @subpackage Widgets
 * @author     Greg Thornton <xdissent@gmail.com>
 * @copyright  2009 Greg Thornton
 * @license    http://creativecommons.org/licenses/by-sa/3.0/us/
 * @link       http://xdissent.github.com/dforms/
 */
abstract class DForms_Widgets_Widget extends DForms_Media_MediaDefiningClass
{

    public $is_hidden = false;
    
    public $needs_multipart_form = false;
    
    public $attrs;
    
    /**
     * The constructor.
     *
     * @return null
     */
    public function __construct($attrs=null)
    {
        if (is_null($attrs)) {
            $attrs = array();
        }
        $this->attrs = $attrs;
    }
    
    /**
     * Renders the widget instance as a string.
     *
     * @return string
     */
    abstract public function render($name, $value, $attrs=null);
    
    /**
     * Merges extra attributes with attributes and returns the combined array.
     *
     * @return array
     */
    protected function buildAttrs($attrs=null, $extra_attrs=null)
    {
        if (is_null($attrs)) {
            $attrs = array();
        }
        if (!is_null($extra_attrs)) {
            $attrs = array_merge($attrs, $extra_attrs);
        }
        return $attrs;
    }
    
    /**
     * Returns the value of this widget determined by the data and name.
     *
     * @return mixed
     */
    public function valueFromData($data, $files, $name)
    {
        if (array_key_exists($name, $data)) {
            return $data[$name];
        }
        
        return null;
    }
    
    /**
     * Determines whether or not the value of this widget has changed.
     *
     * @return boolean
     */
    public function has_changed($initial, $data) {
        if (is_null($data)) {
            $data = '';
        }
        
        if (is_null($initial)) {
            $initial = '';
        }
        
        if ($initial != $data) {
            return true;
        }
        
        return false;
    }
}