<?php
/**
 * Media Defining Class
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
 * @category   HTML
 * @package    DForms
 * @subpackage Media
 * @author     Greg Thornton <xdissent@gmail.com>
 * @copyright  2009 Greg Thornton
 * @license    http://creativecommons.org/licenses/by-sa/3.0/us/
 * @link       http://xdissent.github.com/dforms/
 */

namespace DForms\Media;

/**
 * A base class that provides media statically, or optionally per instance.
 *
 * Subclasses define their media on a class basis by overriding the 
 * ``media`` static method. Instances may simply supply their own
 * ``media`` member variable, which will short circuit the dynamically
 * generated value (since ``__get()`` won't be called at all for ``media``).
 *
 * Media is inherited by subclasses even if they do not define their own media.
 *
 * @category   HTML
 * @package    DForms
 * @subpackage Media
 * @author     Greg Thornton <xdissent@gmail.com>
 * @copyright  2009 Greg Thornton
 * @license    http://creativecommons.org/licenses/by-sa/3.0/us/
 * @link       http://xdissent.github.com/dforms/
 */
abstract class MediaDefiningClass
{
    private $_media;
    
    /**
     * Catches requests for ``media`` property and returns media for the class.
     *
     * @return array
     */
    public function __get($name)
    {
        /**
         * Intercept requests for ``media`` property.
         */
        if ($name == 'media') {
            /**
             * Return the form's media.
             */
            if (is_null($this->_media)) {
                $this->_media = $this->getDefinedMedia();
            }
            return $this->_media;
        }
    }

    /**
     * Returns the defined media for the class with inheritance.
     *
     * @return array
     */
    protected function getDefinedMedia($class=null)
    {
        /**
         * Determine the class name.
         */
        if (is_null($class)) {
            $class = get_class($this);
        }
        
        /**
         * Get the class's defined media array.
         *
         * PHP5.3 equivalent::
         *
         *     $media_array = static::media();
         */
        $media_array = call_user_func(array($class, 'media'));
        
        /**
         * Create a media object from the media array.
         */
        $media = new Media($media_array);
        
        /**
         * Determine the parent class of the form.
         */
        $parent = get_parent_class($class);
        
        /**
         * Bail early if we're dealing with a direct subclass of the base class.
         */
        if ($parent == __CLASS__) {
            return $media;
        }
                
        /**
         * Get parent class media.
         */
        $parent_media = $this->getDefinedMedia($parent);
                
        /**
         * Combine the parent media with the sub class media.
         */
        $media = $parent_media->mergeMedia($media);
               
        /**
         * Return the media.
         */
        return $media;
    }
    
    /**
     * Defines the media for the class.
     *
     * Subclasses should override this method to return an associative array
     * of media paths. Keys should be the type of media (currently only ``js``
     * and ``css``) and values should be relative or absolute URLs. Media is
     * inherited by subclasses *automatically*, and subclasses shoud *not*
     * call the parent method.
     *
     * .. note:: This method must remain public until PHP5.3 since it must be
     *    accessed from ``call_user_func()``. It should eventually be protected.
     */
    public static function media()
    {
        /**
         * An empty array is a default.
         */
        return array();
    }
}