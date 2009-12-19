<?php
/**
 * Form
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
 * @subpackage Forms
 * @author     Greg Thornton <xdissent@gmail.com>
 * @copyright  2009 Greg Thornton
 * @license    http://creativecommons.org/licenses/by-sa/3.0/us/
 * @link       http://xdissent.github.com/dforms/
 */

/**
 * The DForms form base class.
 *
 * @category   HTML
 * @package    DForms
 * @subpackage Forms
 * @author     Greg Thornton <xdissent@gmail.com>
 * @copyright  2009 Greg Thornton
 * @license    http://creativecommons.org/licenses/by-sa/3.0/us/
 * @link       http://xdissent.github.com/dforms/
 */
abstract class DForms_Forms_Form extends DForms_Media_MediaDefiningClass
{
    protected $data;
    
    protected $initial;
    
    protected $files;
    
    protected $empty_permitted;
    
    protected $prefix;
    
    protected $label_suffix;
    
    protected $auto_id;
    
    protected $is_bound = false;
    
    protected $base_fields;
    
    public $fields;
    
    public function __construct($data=null, $initial=null, $files=null, 
        $empty_permitted=false, $prefix=null, $label_suffix=':', 
        $auto_id='id_%s'
    ) {
        /**
         * Determine whether this form is bound.
         */
        if (!is_null($data) or !is_null($files)) {
            $this->is_bound = true;
        }
        
        /**
         * Initialize data.
         */
        if (is_null($data)) {
            $data = array();
        }
        $this->data = $data;
        
        /**
         * Initialize initial values.
         */
        if (is_null($initial)) {
            $initial = array();
        }
        $this->initial = $initial;
        
        /**
         * Initialize files.
         */
        if (is_null($files)) {
            $files = array();
        }
        $this->files = array();
        
        /**
         * Initialize the rest.
         */
        $this->empty_permitted = $empty_permitted;
        $this->prefix = $prefix;
        $this->label_suffix = $label_suffix;
        $this->auto_id = $auto_id;
        
        $this->base_fields = self::getDeclaredFields();
        
        /**
         * Deep copy base fields for this instance.
         */
        $this->fields = $this->base_fields;
    }
    
    /**
     * Returns special properties representing the form's fields.
     */
    public function __get($name) {
        if (array_key_exists($name, $this->fields)) {
            return $this->fields[$name];
        }
        return parent::__get($name);
    }
    
    /**
     * Sets special properties representing the form's fields.
     */
    public function __set($name, $value) {
        if (array_key_exists($name, $this->fields)) {
            $this->fields[$name] = $value;
        }
        throw new Exception('Unknown field: ' . $name);
    }
    
    /**
     * Combines all base fields including inherited fields, in reverse order.
     *
     * @return array
     *
     * @todo Cache the base fields.
     */
    protected function getDeclaredFields($class=null) {
        /**
         * Determine the class name of the form.
         */
        if (is_null($class)) {
            $class = get_class($this);
        }
        
        /**
         * Get the fields declared on the form.
         */
        $fields = call_user_func(array($class, 'declareFields'));
        
        /**
         * Determine the parent class of the form.
         */
        $parent = get_parent_class($class);

        /**
         * Bail early if we're dealing with a direct subclass of the base form.
         */
        if ($parent == __CLASS__) {
            return $fields;
        }
        
        /**
         * Recurse and merge parent fields into this form's fields.
         */
        $fields = array_merge($this->getDeclaredFields($parent), $fields);
        
        /**
         * Return the merged fields.
         */
        return $fields;
    }
    
    /**
     * Returns true if a field is multipart-encoded.
     *
     * @return boolean
     */
    public function isMultipart() {
        /**
         * Traverse each field and return true if a widget needs multipart.
         */
        foreach ($this->fields as &$field) {
            if ($field->widget->needs_multipart_form) {
                return true;
            }
        }
        /**
         * Return false because no fields required multipart.
         */
        return false;
    }
    
    /**
     * Declare the fields for this form class.
     *
     * This method should return an associative array of fields.
     *
     * Example::
     * 
     *     public static function declareFields() {
     *         return array(
     *             'name' => new DForms_Fields_CharField(255),
     *             'email' => new DForms_Fields_EmailField()
     *         );
     *     }
     */
    abstract public static function declareFields();
    
    /**
     * Get all media for the form.
     *
     * Subclasses may override the ``defineMedia()`` static method to define
     * form media in addition to the field media.
     */
    protected function getDefinedMedia($class=null)
    {
        if (!is_null($class)) {
            return parent::getDefinedMedia($class);
        }
        
        $media = parent::getDefinedMedia(get_class($this));
        
        /**
         * Merge in field media.
         */
        $media['js'] = array_merge(array('asdf'), $media['js']);
        
        return $media;
    }
}