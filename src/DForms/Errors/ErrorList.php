<?php
/**
 * Error list
 *
 * This file defines the error list class.
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
 
namespace DForms\Errors;

/**
 * A list of errors with special output rendering methods.
 *
 * The errors contained in the list may be accessed using familiar
 * array syntax:
 *
 * <code>
 * use DForms\Errors\ErrorList;
 *
 * $messages = array('A test error occurred.');
 *
 * // Create an error list from existing array.
 * $errors = new ErrorList($messages);
 *
 * // Loop over each error in the list.
 * foreach ($errors as $msg) {
 *     echo sprintf('Found error: %s', $msg);
 * }
 *
 * // Remove an error from the dictionary.
 * unset($errors[0]);
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
class ErrorList extends \ArrayObject
{
    /**
     * Returns the error list rendered as HTML.
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
        
        foreach($this as $error) {
            $output[] = sprintf('<li>%s</li>', $error);
        }
        
        $output[] = '</ul>';
        return implode($output);
    }
}