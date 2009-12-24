<?php
/**
 * Radio select widget
 *
 * This file defines a select widget.
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
 * The radio select widget
 *
 * @category   HTML
 * @package    DForms
 * @subpackage Widgets
 * @author     Greg Thornton <xdissent@gmail.com>
 * @copyright  2009 Greg Thornton
 * @license    http://creativecommons.org/licenses/by-sa/3.0/us/
 * @link       http://xdissent.github.com/dforms/
 */
class DForms_Widgets_RadioSelect extends DForms_Widgets_Select
{
    protected $renderer = 'DForms_Widgets_RadioFieldRenderer';
    
    public function __construct($attrs=null, $choices=null, $renderer=null)
    {
        if (!is_null($renderer)) {
            $this->renderer = $renderer;
        }
        
        parent::__construct($attrs, $choices);
    }
    
    public function getRenderer($name, $value=null, $attrs=null, $choices=null)
    {
        if (is_null($value)) {
            $value = '';
        }
        
        if (is_null($choices)) {
            $choices = array();
        }
        
        $attrs = $this->buildAttrs($attrs);
        
        $choices = array_merge($this->choices, $choices);
        
        return new $this->renderer($name, $value, $attrs, $choices);
    }
    
    public function render($name, $value, $attrs=null, $choices=null)
    {
        $renderer = $this->getRenderer($name, $value, $attrs, $choices);
        return $renderer->render();
    }
    
    public function idForLabel($id)
    {
        if ($id) {
            $id .= '_0';
        }
        return $id;
    }
}
