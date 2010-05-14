<?php
/**
 * Multiple select widget
 *
 * This file defines a multiple choice select widget.
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
 
namespace DForms\Widgets;

use DForms\Utils\Attributes;

/**
 * A multiple choice select widget.
 *
 * @category   HTML
 * @package    DForms
 * @subpackage Widgets
 * @author     Greg Thornton <xdissent@gmail.com>
 * @copyright  2009 Greg Thornton
 * @license    http://creativecommons.org/licenses/by-sa/3.0/us/
 * @link       http://xdissent.github.com/dforms/
 */
class SelectMultiple extends Select
{
    /**
     * Renders the select widget to HTML.
     *
     * @param string $name    The name to use for the widget.
     * @param mixed  $value   The value to render into the widget.
     * @param array  $attrs   The attributes to use when rendering the widget.
     * @param array  $choices Additional choices to use for the select.
     *
     * @return string
     */
    public function render($name, $value, $attrs=null, $choices=null)
    {
        /**
         * Protect against null values.
         */
        if (is_null($value)) {
            $value = '';
        }
        
        /**
         * Finalize widget attributes.
         */
        $attrs = $this->buildAttrs(
            array(
                'name' => $name
            ),
            $attrs
        );
        
        /**
         * Add an array indicator to the name so PHP doesn't fail.
         */
        $attrs['name'] = $attrs['name'] . '[]';
        
        /**
         * Initialize output array.
         */
        $ouput = array();
        
        /**
         * Open the select tag.
         */
        $output[] = sprintf(
            '<select multiple="multiple"%s>',
            Attributes::flatten($attrs)
        );
        
        /**
         * Render the options.
         */
        $output[] = $this->renderOptions($choices, $value);
        
        /**
         * Close the select tag.
         */
        $output[] = '</select>';
        
        return implode("\n", $output);
    }
    
    /**
     * Determines whether or not the value of this widget has changed.
     *
     * @param array $initial The initial data array.
     * @param array $data    The data array.
     *
     * @return boolean
     */
    public function hasChanged($initial, $data) {
        /**
         * Ensure the initial data is an array.
         */
        if (is_null($data)) {
            $data = array();
        }
        
        /**
         * Ensure the initial data is an array.
         */
        if (is_null($initial)) {
            $initial = array();
        }
        
        /**
         * PHP array comparisons are just what we want here.
         */
        if ($initial != $data) {
            return true;
        }
        
        return false;
    }
}