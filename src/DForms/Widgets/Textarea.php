<?php
/**
 * Textarea widget
 *
 * This file defines a form field widget base class.
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
 * The textarea widget
 *
 * @category   HTML
 * @package    DForms
 * @subpackage Widgets
 * @author     Greg Thornton <xdissent@gmail.com>
 * @copyright  2009 Greg Thornton
 * @license    http://creativecommons.org/licenses/by-sa/3.0/us/
 * @link       http://xdissent.github.com/dforms/
 */
class DForms_Widgets_Textarea extends DForms_Widgets_Widget
{
    public function __construct($attrs=null)
    {
        $default_attrs = array(
            'cols' => '40',
            'rows' => '10'
        );
        
        if (!is_null($attrs)) {
            $default_attrs = array_merge($default_attrs, $attrs);
        }

        parent::__construct($default_attrs);
    }
    
    public function render($name, $value, $attrs=null)
    {
        if (is_null($value)) {
            $value = '';
        }
        
        $attrs = $this->buildAttrs(array('name' => $name), $attrs);
        
        return sprintf(
            '<textarea%s>%s</textarea>',
            DForms_Utils_Attributes::flatten($attrs),
            htmlentities($value)
        );
    }
}