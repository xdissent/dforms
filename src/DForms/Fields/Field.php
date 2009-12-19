<?php
/**
 * Field
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
 * @subpackage Fields
 * @author     Greg Thornton <xdissent@gmail.com>
 * @copyright  2009 Greg Thornton
 * @license    http://creativecommons.org/licenses/by-sa/3.0/us/
 * @link       http://xdissent.github.com/dforms/
 */

/**
 * The DForms field base class.
 *
 * @category   HTML
 * @package    DForms
 * @subpackage Fields
 * @author     Greg Thornton <xdissent@gmail.com>
 * @copyright  2009 Greg Thornton
 * @license    http://creativecommons.org/licenses/by-sa/3.0/us/
 * @link       http://xdissent.github.com/dforms/
 */
abstract class DForms_Fields_Field
{
    protected $label;
    
    protected $help_text;
    
    protected $initial;
    
    protected $required;
    
    protected $widget = 'DForms_Widgets_TextInput';
    
    protected $error_messages;
    
    protected $show_hidden_initial;

    /**
     * Instances that wish to override the widget with a widget *instance*
     * should do so in their constructor *before* calling this constructor.
     */
    public function __construct($label=null, $help_text=null, $initial=null,
        $required=true, $widget=null, $error_messages=null, 
        $show_hidden_initial=false
    ) {
        /**
         * Initialize members.
         */
        $this->label = $label;
        $this->help_text = $help_text;
        $this->initial = $initial;
        $this->required = $required;
        
        /**
         * Provide a default text input widget.
         */
        if (is_null($widget)) {
            $widget = $this->widget;
        }
        
        /**
         * If passed a widget class name, instantiate it.
         */
        if (is_string($widget)) {
            $widget = new $widget;
        }
        
        /**
         * Set extra widget attrs.
         */
        
        /**
         * Store the widget instance.
         */
        $this->widget = $widget;
        
        /**
         * Handle default error messages.
         */
        $this->error_messages = $error_messages;
    }
}