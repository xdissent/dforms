<?php
/**
 * Multiple hidden input widget
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
 * The hidden input widget
 *
 * @category   HTML
 * @package    DForms
 * @subpackage Widgets
 * @author     Greg Thornton <xdissent@gmail.com>
 * @copyright  2009 Greg Thornton
 * @license    http://creativecommons.org/licenses/by-sa/3.0/us/
 * @link       http://xdissent.github.com/dforms/
 */
class DForms_Widgets_MultipleHiddenInput extends DForms_Widgets_HiddenInput
{
    /**
     * Creates a select widget.
     *
     * @param array $attrs   The attributes to use when rendering the widget.
     * @param array $choices The choices to use for select options.
     *
     * @return null
     */
    public function __construct($attrs=null, $choices=null)
    {
        /**
         * Run default widget constructor.
         */
        parent::__construct($attrs);
        
        /**
         * Initialize choices array if not given.
         */
        if (is_null($choices)) {
            $choices = array();
        }
        
        /**
         * Store widget choices.
         */
        $this->choices = $choices;
    }
    
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
            $value = array();
        }
        
        /**
         * Finalize widget attributes.
         */
        $attrs = $this->buildAttrs(
            array(
                'type' => $this->input_type,
                'name' => $name
            ),
            $attrs
        );
        
        /**
         * Add an array indicator to the name so PHP doesn't fail.
         */
        $attrs['name'] = $attrs['name'] . '[]';
        
        $output = array();
        
        /**
         * Add a hidden input for each value.
         */
        foreach ($value as $v) {
            $vattrs = array('value' => $v)
            $vattrs = array_merge($vattrs, $attrs);
            $output[] = sprintf(
                '<input%s />',
                DForms_Utils_Attributes::flatten($vattrs)
            );
        }
        
        return implode("\n", $output);
    }
}