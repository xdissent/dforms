<?php
/**
 * Attributes utility
 *
 * This file defines a utility used to flatten and manipulate arrays of 
 * attributes.
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
 * @category   Utilities
 * @package    DForms
 * @subpackage Utils
 * @author     Greg Thornton <xdissent@gmail.com>
 * @copyright  2009 Greg Thornton
 * @license    http://creativecommons.org/licenses/by-sa/3.0/us/
 * @link       http://xdissent.github.com/dforms/
 */
 
namespace DForms\Utils;

/**
 * An attribute manipulation utility.
 *
 * @category   Utilities
 * @package    DForms
 * @subpackage Utils
 * @author     Greg Thornton <xdissent@gmail.com>
 * @copyright  2009 Greg Thornton
 * @license    http://creativecommons.org/licenses/by-sa/3.0/us/
 * @link       http://xdissent.github.com/dforms/
 */
class Attributes
{
    /**
     * Returns a flattened string of attributes suitable for use in HTML tags.
     *
     * @return string
     */
    public static function flatten($attrs) {
        $flat = '';
        
        if (!is_array($attrs)) {
            return $flat;
        }
        
        foreach ($attrs as $name => $value) {
            $flat .= sprintf(' %s="%s"', $name, htmlentities($value));
        }
        return $flat;
    }
}