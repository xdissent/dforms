<?php
/**
 * DForms Import Script
 *
 * Including this script will register the DForms auto loader, allowing 
 * immediate use of all classes in the DForms library from anywhere within
 * the rest of your project.
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
 * @author     Greg Thornton <xdissent@gmail.com>
 * @copyright  2009 Greg Thornton
 * @license    http://creativecommons.org/licenses/by-sa/3.0/us/
 * @link       http://xdissent.github.com/dforms/
 */

/**
 * Import the DForms auto loader from a relative path.
 */
require_once dirname(__FILE__) . '/Utils/AutoLoader.php';

/**
 * Register the auto loader with PHP.
 */
DForms_Utils_AutoLoader::register();

/**
 * Load the debugger if requested.
 */
if (defined('DFORMS_DEBUG')) {
    DForms_Utils_Debugger::register();
}