<?php
/**
 * Input widget
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
 * The base input widget.
 *
 * @category   HTML
 * @package    DForms
 * @subpackage Widgets
 * @author     Greg Thornton <xdissent@gmail.com>
 * @copyright  2009 Greg Thornton
 * @license    http://creativecommons.org/licenses/by-sa/3.0/us/
 * @link       http://xdissent.github.com/dforms/
 */
abstract class DForms_Widgets_Input extends DForms_Widgets_Widget
{
    /**
     * The input type to use for the widget.
     *
     * This member variable allows different widget subclasses to use the
     * input widget base class for most types of input.
     *
     * @var string
     */
    protected $input_type;
    
    /**
     * Renders the select widget to HTML.
     *
     * @param string $name    The name to use for the widget.
     * @param mixed  $value   The value to render into the widget.
     * @param array  $attrs   The attributes to use when rendering the widget.
     *
     * @return string
     */
    public function render($name, $value, $attrs=null)
    {
        if (is_null($value)) {
            $value = '';
        }
        
        $attrs = $this->buildAttrs(
            array(
                'type' => $this->input_type,
                'name' => $name
            ),
            $attrs
        );
        
        if ($value != '') {
            $attrs['value'] = $value;
        }
        
        return sprintf('<input%s />', DForms_Utils_Attributes::flatten($attrs));
    }
}