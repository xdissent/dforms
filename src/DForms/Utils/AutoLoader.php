<?php
/**
 * Auto Loader
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
 * @category   Utilities
 * @package    DForms
 * @subpackage Utils
 * @author     Greg Thornton <xdissent@gmail.com>
 * @copyright  2009 Greg Thornton
 * @license    http://creativecommons.org/licenses/by-sa/3.0/us/
 * @link       http://xdissent.github.com/dforms/
 */

/**
 * The DForms auto loader.
 *
 * To install this auto loader, include this file and call the loader's static 
 * register method.
 *
 * @category   Utilities
 * @package    DForms
 * @subpackage Utils
 * @author     Greg Thornton <xdissent@gmail.com>
 * @copyright  2009 Greg Thornton
 * @license    http://creativecommons.org/licenses/by-sa/3.0/us/
 * @link       http://xdissent.github.com/dforms/
 */
class DForms_Utils_AutoLoader
{
    /**
     * The extension to use when locating class names.
     * 
     * @var string
     */
    protected static $extension = '.php';
    
    /**
     * A flag to indicate if this auto loader has been registered.
     *
     * @var boolean
     */
    protected static $registered = false;
    
    /**
     * Registers the auto loader with PHP's auto loading system.
     * 
     * @return boolean
     */
    public static function register()
    {
        /**
         * Determine if we've already been registered successfully.
         */
        if (!self::$registered) {
            /**
             * Try to register with PHP.
             */
            self::$registered = spl_autoload_register(
                array(__CLASS__, 'autoLoad')
            );
        }
        /**
         * Return a boolean representing registration success.
         */
        return self::$registered;
    }

    /**
     * Loads the file in which a class is defined based on its name.
     *
     * This static method includes the file in which a class is defined by
     * translating the class name into a file path. Class names should follow
     * the PEAR standard convention of 'Package_Subpackage_ClassName', although
     * other schemes ('Package_ClassName' for the lazy) may work as well. The
     * translated path will be prefixed with the absolute path of the directory
     * containing this file, minus the number of levels suggested by this 
     * class's name. This is done to compensate for the package and (possible)
     * subpackage directories in which this file will be stored.
     *
     * For example, if this class is located in 
     * '/home/xdissent/Code/DForms/Utils/AutoLoader.php' and named 
     * 'DForms_Utils_AutoLoader', the prefix used would be 
     * '/home/xdissent/Code' and autoloading the class 
     * 'Package_Subpackage_ClassName' would include the file 
     * '/home/xdissent/Code/Package/Subpackage/ClassName.php'.
     *
     * This method must remain public to be used with 'spl_autoload_register'.
     *
     * @param string $class The name of the class to load.
     *
     * @return boolean
     */
    public static function autoLoad($class)
    {
        /**
         * Determine the absolute path prefix to use.
         */
        $num_name_elements = count(explode('_', __CLASS__));
        $prefix = explode(DIRECTORY_SEPARATOR, __FILE__);
        
        /**
         * Remove the auto loader class name components from the prefix.
         */
        $prefix = array_slice($prefix, 0, count($prefix) - $num_name_elements);
        
        /**
         * Collapse the prefix array into an absolute path prefix.
         */
        $prefix = implode(DIRECTORY_SEPARATOR, $prefix);
         
        /**
         * Split the requested class name by underscores to find the path 
         * components.
         */
        $path = explode('_', $class);
        
        /**
         * Add the prefix as a path component.
         */
        array_unshift($path, $prefix);
        
        /**
         * Combine the path component to construct the path as a string.
         */
        $path = implode(DIRECTORY_SEPARATOR, $path);
        
        /**
         * Add this class's defined extension to get the final include path.
         */
        $path .= self::$extension;
        
        /**
         * Check to see if the include file exists before including.
         */
        if (!file_exists($path)) {
            /**
             * If the file isn't there, pass control to the next auto loader.
             */
            return false;
        }
        
        /**
         * Include the file that should contain the requested class. If it does
         * *not* include the requested class, the next autoloader will try to
         * locate it.
         */
        include_once $path;

        /**
         * Return true to short circuit other auto loaders.
         */
        return true;
    }
}