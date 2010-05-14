<?php
/**
 * Multiple choice field
 *
 * This file defines a drop down field with multiple select values,
 * {@link DForms_Fields_MultipleChoiceField}.
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

namespace DForms\Fields;

use DForms\Errors\ValidationError;

/**
 * The multiple choice field.
 *
 * @category   HTML
 * @package    DForms
 * @subpackage Fields
 * @author     Greg Thornton <xdissent@gmail.com>
 * @copyright  2009 Greg Thornton
 * @license    http://creativecommons.org/licenses/by-sa/3.0/us/
 * @link       http://xdissent.github.com/dforms/
 */
class MultipleChoiceField extends ChoiceField
{
    public $widget = 'DForms\Widgets\SelectMultiple';
    public $hidden_widget = 'DForms\Widgets\MultipleHiddenInput';

    /**
     * Returns the error messages to use by default for the field.
     *
     * @return array
     */
    public static function errorMessages()
    {
        return array(
            'invalid_list' => 'Enter a list of values.',
        );
    }
    
    /**
     * Validates the given value is in the available choices.
     *
     * @param mixed $value The value to clean.
     *
     * @throws ValidationError
     * @return mixed
     */
    public function clean($value)
    {
        /**
         * Check to see if the field is required.
         */
        if ($this->required && $this->isEmptyValue($value)) {
            /**
             * Throw a validation error indicating the field value is missing.
             */
            throw new ValidationError(
                $this->error_messages['required']
            );
        } elseif (!$this->required && $this->isEmptyValue($value)) {
            return array();
        }
        
        /**
         * Make sure we don't have an empty value.
         */
        if (!is_array($value)) {
            throw new ValidationError(
                $this->error_messages['invalid_list']
            );
        }
        
        /**
         * Verify that each value in the value list is in the choices.
         */
        foreach ($value as $val) {
            if (!$this->validValue($val)) {
                throw new ValidationError(
                    sprintf($this->error_messages['invalid_choice'], $val)
                );
            }
        }
        
        return $value;
    }
}