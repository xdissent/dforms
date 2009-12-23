<?php
/**
 * Choice field
 *
 * This file defines a drop down choice field.
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
 * The choice field.
 *
 * @category   HTML
 * @package    DForms
 * @subpackage Fields
 * @author     Greg Thornton <xdissent@gmail.com>
 * @copyright  2009 Greg Thornton
 * @license    http://creativecommons.org/licenses/by-sa/3.0/us/
 * @link       http://xdissent.github.com/dforms/
 */
class DForms_Fields_ChoiceField extends DForms_Fields_Field
{
    public $widget = 'DForms_Widgets_Select';
    
    private $_choices;
    
    /**
     * Instantiates a select field.
     *
     * @param mixed   $label               The label to display for the field. 
     *                                     Pass null for the class default.
     * @param mixed   $help_text           The help text to display for the  
     *                                     field. Pass null for the class
     *                                     default.
     * @param mixed   $initial             The initial value to use for the 
     *                                     field. Pass null for the class 
     *                                     default.
     * @param boolean $required            A flag indicating whether a field is 
     *                                     required.
     * @param mixed   $widget              The class name or instance of the 
     *                                     widget for the field.
     * @param array   $error_messages      An array of error messages for the 
     *                                     field.
     * @param boolean $show_hidden_initial A flag indicating whether a field
     *                                     should be rendered with a hidden
     *                                     widget containing the initial value.
     *
     * @return null
     */
    public function __construct($label=null, $help_text=null, $choices=null,
        $initial=null, $required=true, $widget=null, $error_messages=null, 
        $show_hidden_initial=false
    ) {
        parent::__construct(
            $label, 
            $help_text, 
            $initial, 
            $required, 
            $widget, 
            $error_messages, 
            $show_hidden_initial
        );
        
        if (is_null($choices)) {
            $choices = array();
        }
        $this->choices = $choices;
    }
    
    /**
     * Returns the dynamic choices member variable.
     *
     * @param string $name The name of the dynamic member variable to return.
     *
     * @return array
     */
    public function __get($name)
    {
        if ($name == 'choices') {
            return $this->_choices;
        }
    }
    
    /**
     * Sets the choices dynamic member variable and the widget's choices.
     *
     * @param string $name  The name of the dynamic member variable to set.
     * @param mixed  $value The new value for the dynamic variable.
     *
     * @return null
     */
    public function __set($name, $value)
    {
        if ($name == 'choices') {
            $this->_choices = $value;
            $this->widget->choices = $value;
        }
    }

    /**
     * Returns the error messages to use by default for the field.
     *
     * Field classes may override this static method to provide extra error
     * messages specific to the field type.
     *
     * @return array
     */
    public static function errorMessages()
    {
        return array(
            'invalid_choice' => 'Select a valid choice. %s is not one of the available choices.',
        );
    }
    
    /**
     * Validates the given value is in the available choices.
     *
     * @param mixed $value The value to clean.
     *
     * @throws DForms_Errors_ValidationError
     * @return mixed
     */
    public function clean($value)
    {
        $value = parent::clean($value);
        
        /**
         * Make sure we don't have an empty value.
         */
        if ($this->isEmptyValue($value)) {
            $value = '';
        }
        
        if ($value == '') {
            return $value;
        }
        
        if (!$this->validValue($value)) {
            throw new DForms_Errors_ValidationError(
                sprintf($this->error_messages['invalid_choice'], $value)
            );
        }
        
        return $value;
    }
    
    /**
     * Returns a boolean indicating whether a value is a valid choice.
     *
     * @param string $value The value to check for validity.
     *
     * @return boolean
     */
    protected function validValue($value)
    {
        $choices = $this->choices;
        foreach ($choices as $k => $v) {
            if (is_array($v)) {
                foreach ($v as $k2 => $v2) {
                    if ($value == $k2) {
                        return true;
                    }
                }
            } else {
                if ($value == $k) {
                    return true;
                }
            }
        }
        return false;
    }
}