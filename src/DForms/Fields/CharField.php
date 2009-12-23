<?php
/**
 * Character field
 *
 * This file defines the base field class.
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
 * The character field.
 *
 * @category   HTML
 * @package    DForms
 * @subpackage Fields
 * @author     Greg Thornton <xdissent@gmail.com>
 * @copyright  2009 Greg Thornton
 * @license    http://creativecommons.org/licenses/by-sa/3.0/us/
 * @link       http://xdissent.github.com/dforms/
 */
class DForms_Fields_CharField extends DForms_Fields_Field
{
    public function __construct($label=null, $help_text=null, 
        $max_length=null, $min_length=null, $initial=null,
        $required=true, $widget=null, $error_messages=null, 
        $show_hidden_initial=false)
    {
        $this->max_length = $max_length;
        $this->min_length = $min_length;
        
        parent::__construct(
            $label, 
            $help_text, 
            $initial, 
            $required, 
            $widget,
            $error_messages,
            $show_hidden_initial
        );
    }
    
    public function clean($value)
    {
        /**
         * Call the inherited clean method.
         */
        parent::clean($value);
        
        /**
         * Make sure we don't have an empty value.
         */
        if ($this->isEmptyValue($value)) {
            $value = '';
        }
        
        $value_length = strlen($value_length);
        
        if (!is_null($this->max_length) && $value_length > $this->max_length) {
            throw new DForms_Errors_ValidationError(
                sprintf(
                    $this->error_messages['max_length'],
                    $this->max_length, 
                    $value_length
                )
            );
        }
        
        if (!is_null($this->min_length) && $value_length < $this->min_length) {
            throw new DForms_Errors_ValidationError(
                sprintf(
                    $this->error_messages['min_length'],
                    $this->min_length, 
                    $value_length
                )
            );
        }
        
        return $value;
    }
    
    public static function errorMessages() {
        return array(
            'max_length' => 'Ensure this value has at most %1$d characters (it has %2$d).',
            'min_length' => 'Ensure this value has at least %1$d characters (it has %2$d).',
        );
    }
    
    public function widgetAttrs($widget) {

        if (!is_null($this->max_length)
            && ($widget instanceof DForms_Widgets_TextInput
                || $widget instanceof DForms_Widgets_PasswordInput)
        ) {
            return array('maxlength' => (string)$this->max_length);
        }
        
        return array();
    }
}