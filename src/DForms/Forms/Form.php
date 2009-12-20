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
    const NON_FIELD_ERRORS = '__all__';
    /**
     * Bound form data.
     *
     * The data that represents the current state of the form.
     *
     * @var array
     */
    protected $data;
    
    /**
     * Initial form data.
     *
     * This initial data array is used to fill in the initial values of an
     * unbound form. It is *not* used as a set of defaults for missing fields
     * in the forms data.
     *
     * @var array
     */
    protected $initial;
    
    /**
     * File form data.
     *
     * @var array
     */
    protected $files;
        
    protected $prefix;
    
    protected $label_suffix;
    
    protected $auto_id;
    
    /**
     * The error class to use for the form.
     *
     * @var string
     */
    protected $error_class = 'DForms_Errors_ErrorList';
    
    /**
     * A flag to indicate whether empty fields are allowed on the form.
     *
     * @var boolean
     */
    protected $empty_permitted;
    
    /**
     * A flag to indicate whether the form has bound data.
     *
     * @var boolean
     */
    protected $is_bound = false;
    
    /**
     * Class level field array including inherited fields.
     *
     * It's important to store a copy of the fields that are defined by default
     * for a field, including inherited fields. Instances can then differentiate
     * between inherited fields and instance specific fields. Manipulating 
     * instance fields is always done with the ``fields`` member variable
     * so this base field array remains clean.
     */
    protected $base_fields;
    
    /**
     * The fields for the form.
     *
     * This associative array of fields is populated upon instantiation to
     * a list of inherited fields and those defined by the field class. These
     * may be manipulated after that point to provide form instance specific
     * field arrangments.
     *
     * .. note:: When looping through this field array, it's *required* that
     *    the field instance is passed by reference. Otherwise, the stored
     *    fields will not be modified. Then you *must* ``unset()`` the variable
     *    that was receiving the field instance reference after the loop.
     *
     * @var array
     */
    public $fields;
    
    /**
     * The form's errors.
     *
     * The error list will be ``null`` until the form is cleaned. It may be
     * accessed publicly from the ``errors`` dynamic member variable and
     * the form will be cleaned if it hasn't already. It will almost certainly
     * be an instance of the ``error_class`` member variable.
     *
     * @var object
     */
    private $_errors;
    
    /**
     * Construct a form.
     */
    public function __construct($data=null, $initial=null, $files=null, 
        $prefix=null, $label_suffix=':', $auto_id='id_%s', $error_class=null,
        $empty_permitted=false
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
         * Initialize error class.
         */
        if (is_null($error_class)) {
            $error_class = $this->error_class;
        }
        $this->error_class = $error_class;
        
        /**
         * Initialize the rest.
         */
        $this->prefix = $prefix;
        $this->label_suffix = $label_suffix;
        $this->auto_id = $auto_id;
        $this->empty_permitted = $empty_permitted;
        
        /**
         * Get the declared and inherited fields.
         */
        $this->base_fields = $this->getDeclaredFields();
        
        /**
         * Deep copy base fields for this instance.
         */
        $this->fields = $this->base_fields;
    }
    
    /**
     * Returns special properties representing the form's fields.
     *
     * For each field in the form instance, a dynamic member variable is made
     * available based on the key of the field's name.
     *
     * The dynamic ``media`` member variable is combined with all media found
     * in each field in the form, therefore representing all media required
     * to correctly render the entire form and all fields.
     *
     * @param string $name The name of the dynamic member variable to retrieve.
     *
     * @return mixed
     */
    public function __get($name)
    {
        /**
         * Check to see if a field with this name exists.
         */
        if (array_key_exists($name, $this->fields)) {
            /**
             * Return the field instance.
             */
            return $this->fields[$name];
        }
        
        /**
         * Update media member variable to include field media.
         */
        if ($name == 'media') {
            /**
             * Get the (inherited) form media like normal.
             */
            $media = parent::__get($name);
            
            /**
             * Add each field's media.
             */
            foreach ($this->fields as &$field) {
                /**
                 * Add the field widget's media to the list.
                 */
                $media = $field->widget->media->mergeMedia($media);
            }
            unset($field);
            
            /**
             * Return the combined media.
             */
            return $media;
        }
        
        /**
         * Error handling.
         */
        if ($name == 'errors') {
            /**
             * Check to see if we've already been cleaned.
             */
            if (is_null($this->_errors)) {
                /**
                 * Run a full clean to generate errors.
                 */
                $this->fullClean();
            }
            
            /**
             * Return the populated errors.
             */
            return $this->_errors;
        }
        
        /**
         * Default handling.
         */
        return parent::__get($name);
    }
    
    /**
     * Sets special properties representing the form's fields.
     */
    public function __set($name, $value)
    {
        if (array_key_exists($name, $this->fields)) {
            $this->fields[$name] = $value;
        }
        throw new Exception('Unknown field: ' . $name);
    }
    
    public function __toString()
    {
        return $this->asTable();
    }
    
    /**
     * Declare the fields for this form class.
     *
     * This method should return an associative array of fields.
     *
     * Example::
     * 
     *     public static function fields() {
     *         return array(
     *             'name' => new DForms_Fields_CharField(),
     *             'email' => new DForms_Fields_EmailField()
     *         );
     *     }
     */
    abstract public static function fields();
    
    /**
     * Combines all base fields including inherited fields, in reverse order.
     *
     * @return array
     */
    protected function getDeclaredFields($class=null)
    {
        /**
         * Determine the class name of the form.
         */
        if (is_null($class)) {
            $class = get_class($this);
        }
        
        /**
         * Get the fields declared on the form.
         */
        $fields = call_user_func(array($class, 'fields'));
        
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
    public function isMultipart()
    {
        /**
         * Check each field for mulitpart requirement.
         */
        foreach ($this->fields as &$field) {
            /**
             * Return true if the field's widget needs a multipart form.
             */
            if ($field->widget->needs_multipart_form) {
                return true;
            }
        }
        unset($field);

        /**
         * Return false because no fields required multipart.
         */
        return false;
    }
    
    /**
     * Returns the form rendered in html.
     *
     * Normal Row:
     * + errors
     * + label
     * + field
     * + help_text
     * + html_class_attr
     *
     * Error Row:
     * + error
     *
     * Row Ender:
     * + (none)
     *
     * Help Text HTML:
     * + help_text
     *
     * @return string
     */
    protected function htmlOutput($normal_row, $error_row, $row_ender, 
        $help_text_html, $errors_on_separate_row
    ) {
        $top_errors = $this->nonFieldErrors();
        $output = array();
        $hidden_fields = array();
        $html_class_attr = '';
        
        foreach ($this->fields as $name => &$field) {
            // process field.
        }
        unset($field);
        
        if ($top_errors->count()) {
            array_unshift($output, sprintf($error_row, $top_errors));
        }
        
        if (count($hidden_fields)) {
            $str_hidden = implode($hidden_fields);
        }
        return implode("\n", $output);
    }
    
    public function asTable()
    {
        return $this->htmlOutput(
            '<tr%5$s><th>%2$s</th><td>%1$s%3$s%4$s</td></tr>',
            '<tr><td colspan="2">%s</td></tr>',
            '</td></tr>',
            '<br />%s',
            false
        );
    }
    
    public function asUL()
    {
        return $this->htmlOutput(
            '<li%5$s>%1$s%2$s %3$s%4$s</li>',
            '<li>%s</li>',
            '</li>',
            ' %s',
            false
        );
    }

    public function asP()
    {
        return $this->htmlOutput(
            '<p%5$s>%2$s %3$s%4$s</p>',
            '%s',
            '</p>',
            ' %s',
            true
        );
    }
    
    public function fullClean()
    {
        $this->_errors = new DForms_Errors_ErrorDict();
    }
    
    protected function nonFieldErrors()
    {
        if ($this->errors->offsetExists(self::NON_FIELD_ERRORS)) {
            return $this->errors->offsetGet(self::NON_FIELD_ERRORS);
        }
        return new $this->error_class;
    }
}