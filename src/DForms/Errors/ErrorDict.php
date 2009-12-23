<?php
/**
 * Error dictionary
 *
 * This file defines the error dictionary class.
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
 * @subpackage Errors
 * @author     Greg Thornton <xdissent@gmail.com>
 * @copyright  2009 Greg Thornton
 * @license    http://creativecommons.org/licenses/by-sa/3.0/us/
 * @link       http://xdissent.github.com/dforms/
 */

/**
 * A dictionary of errors with special output rendering methods.
 *
 * The errors contained in the dictionary may be accessed using familiar
 * associative array syntax:
 *
 * <code>
 * $messages = array('test' => 'A test error occurred.');
 *
 * // Create an error dictionary from existing array.
 * $errors = new DForms_Errors_ErrorDict($messages);
 *
 * // Loop over each error in the dictionary.
 * foreach ($errors as $name => $msg) {
 *     echo sprintf('Found error %s: %s', $name, $msg);
 * }
 *
 * // Remove an error from the dictionary.
 * unset($errors['test']);
 *
 * // Check for errors.
 * if (count($errors)) {
 *     do_something($errors);
 * }
 *
 * // Output errors rendered as html.
 * echo $errors;
 * </code>
 *
 * @category   HTML
 * @package    DForms
 * @subpackage Errors
 * @author     Greg Thornton <xdissent@gmail.com>
 * @copyright  2009 Greg Thornton
 * @license    http://creativecommons.org/licenses/by-sa/3.0/us/
 * @link       http://xdissent.github.com/dforms/
 */
class DForms_Errors_ErrorDict extends ArrayObject
{
    /**
     * Returns an error dictionary rendered as HTML.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->asUL();
    }
    
    /**
     * Returns an error dictionary as an unordered list in HTML.
     *
     * @return string
     */
    public function asUL()
    {
        if (!count($this)) {
            return '';
        }
        
        $output = array();
        $output[] = '<ul class="errorlist">';
        
        foreach ($this as $key => $val) {
            $output[] = sprintf('<li>%s%s</li>', $key, $val);
        }
        
        $output[] = '</ul>';
        return implode($output);
    }
}