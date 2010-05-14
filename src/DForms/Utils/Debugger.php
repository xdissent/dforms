<?php
/**
 * Debugger
 *
 * This file defines a debugger. Don't use this yet.
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
 * The DForms debugger.
 *
 * @category   Utilities
 * @package    DForms
 * @subpackage Utils
 * @author     Greg Thornton <xdissent@gmail.com>
 * @copyright  2009 Greg Thornton
 * @license    http://creativecommons.org/licenses/by-sa/3.0/us/
 * @link       http://xdissent.github.com/dforms/
 */
class Debugger
{
    /**
     * A flag to indicate if this auto loader has been registered.
     *
     * @var boolean
     */
    protected static $registered = false;
    
    /**
     * The previously registered exception handler if any.
     */
    protected static $previous_handler;
    
    /**
     * Registers the debugger with PHP.
     * 
     * @return null
     */
    public static function register()
    {
        /**
         * Determine if we've already been registered successfully.
         */
        if (!self::$registered) {
            /**
             * Register with PHP.
             */
            self::$previous_handler = set_exception_handler(
                array(__CLASS__, 'handleException')
            );
        }
        /**
         * Prevent repeat registration.
         */
        self::$registered = true;
    }
    
    /**
     * Deregister the debugger, restoring previous exception handler.
     * 
     * @return null
     */
    public static function deregister()
    {
        /**
         * Bail if we haven't been registered yet.
         */
        if (!self::$registered) {
            return;
        }
        
        /**
         * Restore the previous exception handler.
         */
        set_exception_handler(self::$previous_handler);
        
        /**
         * Reset registration flag.
         */
        self::$registered = false;
    }
    
    /**
     * Handle a thrown exception.
     *
     * @return null
     */
    public static function handleException($exception) {
        print_r($exception);
    }
    
    public static function html() {
        ?>
            <html>
                <body>
                    <h1>DForms Debugger</h1>
                    <p>This is the debugger.</p>
                </body>
            </html>
        <?php
    }
}