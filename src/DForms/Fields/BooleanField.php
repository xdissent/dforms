<?php
/**
 * Boolean field
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
 * The boolean field.
 *
 * @category   HTML
 * @package    DForms
 * @subpackage Fields
 * @author     Greg Thornton <xdissent@gmail.com>
 * @copyright  2009 Greg Thornton
 * @license    http://creativecommons.org/licenses/by-sa/3.0/us/
 * @link       http://xdissent.github.com/dforms/
 */
class BooleanField extends Field
{
    public $widget = 'DForms\Widgets\CheckboxInput';
    
    public function clean($value)
    {
        if ($value === 'False' || $value === '0') {
            $value = false;
        } else {
            $value = (boolean)$value;
        }
        
        /**
         * Call the inherited clean method.
         */
        parent::clean($value);
        
        /**
         * Make sure we don't have an empty value.
         */
        if (($this->isEmptyValue($value) || $value === false) 
            && $this->required
        ) {
            throw new ValidationError($this->error_messages['required']);
        }
        
        return $value;
    }
}