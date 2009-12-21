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
    /**
     * A key to use for errors in the error list that do not belong ot a field.
     */
    const NON_FIELD_ERRORS = '__all__';
    
    /**
     * Bound form data.
     *
     * The data that represents the current state of the form.
     *
     * .. note:: This member variable must remain public so it can be 
     *    accessed from bound fields belonging to this form.
     *
     * @var array
     */
    public $data;
    
    /**
     * Initial form data.
     *
     * This initial data array is used to fill in the initial values of an
     * unbound form. It is *not* used as a set of defaults for missing fields
     * in the forms data.
     *
     * .. note:: This member variable must remain public so it can be 
     *    accessed from bound fields belonging to this form.
     *
     * @var array
     */
    public $initial;
    
    /**
     * File form data.
     *
     * .. note:: This member variable must remain public so it can be 
     *    accessed from bound fields belonging to this form.
     *
     * @var array
     */
    public $files;

    /**
     * The default field prefix to use.
     *
     * Each field name will be prefixed with the form defined prefix string. 
     * The prefix does not alter the name of the field; just the rendered
     * output. Form classes should not override the value of this member. 
     * Instead, a different prefix may be passed when instantiating a form.
     *
     * @var string
     */
    protected $prefix;
    
    /**
     * The suffix to use when rendering the form field labels.
     *
     * By default, the label suffix is only used if the field label does not
     * end in punctuation. Overriding the default label suffix may only be
     * done when instantiating a form.
     *
     * @var string
     */
    protected $label_suffix;
    
    /**
     * The auto id string template for the form.
     *
     * The auto id is used to generate the id string used when rendering
     * each field's widget.
     *
     * .. note:: This member variable must remain public so it can be 
     *    accessed from bound fields belonging to this form.
     *
     * @var string
     */
    public $auto_id;
    
    /**
     * The error class to use for the form.
     *
     * Errors in the form are instances of this class. The class should
     * implement the array access methods defined by the SPL ``ArrayObject``
     * class.
     *
     * .. note:: This member variable must remain public so it can be 
     *    accessed from bound fields belonging to this form.
     *
     * @var string
     */
    public $error_class = 'DForms_Errors_ErrorList';
    
    /**
     * A flag to indicate whether empty fields are allowed on the form.
     *
     * @var boolean
     */
    protected $empty_permitted;
    
    /**
     * A flag to indicate whether the form has bound data.
     *
     * .. note:: This member variable must remain public so it can be 
     *    accessed from bound fields belonging to this form.
     *
     * @var boolean
     */
    public $is_bound = false;
    
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
     * The field names which differ from their initial value.
     *
     * The changed data list consists of field names, not the actual data. It
     * may be accessed through the ``changed_data`` dynamic member variable. The
     * form also has a convenience method, ``hasChanged()`` which returns a 
     * boolean indicated whether this member variable is non-empty.
     */
    private $_changed_data;
    
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
         * Initialize the member variables that can't be overridden by the class.
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
         * Copy base fields for this instance.
         */
        $this->fields = $this->base_fields;
    }
    
    /**
     * Returns special properties representing the form's fields.
     *
     * For each field in the form instance, a dynamic member variable is made
     * available based on the key of the field's name. The field corresponding
     * to that name will be used to create a bound field instance, which is
     * returned.
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
             * Return the field as a bound field instance.
             */
            return new DForms_Fields_BoundField(
                $this, 
                $this->fields[$name],
                $name
            );
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
         * Changed data dynamic property.
         */
        if ($name == 'changed_data') {
            return $this->getChangedData();
        }
        
        /**
         * Default handling.
         */
        return parent::__get($name);
    }
    
    /**
     * Returns the rendered HTML form.
     *
     * @return string
     */
    public function __toString()
    {
        /**
         * Renders the form as a table by default.
         */
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
     *
     * .. note:: This static method must remain public until PHP5.3 since it is
     *    accessed by ``call_user_func()``.
     */
    abstract public static function fields();
    
    /**
     * Combines all base fields including inherited fields, in reverse order.
     *
     * @param string $class The class to retrieve fields from. Pass ``null`` to
     *                      get all inherited fields for a form.
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
     * Returns true if any field in the form needs to be multipart-encoded.
     *
     * Use this method to determine the ``enctype`` attribute when opening
     * the ``<form>`` tag.
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
     * Substitutions used on parameters:
     *
     * normal_row:
     * + errors
     * + label
     * + field
     * + help_text
     * + html_class_attr
     *
     * error_row:
     * + error
     *
     * row_ender:
     * + (none)
     *
     * help_text_html:
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
    
    /**
     * Cleans the forms data, and populates the errors and cleaned_data.
     *
     * @return null
     */
    protected function fullClean()
    {
        /**
         * Set up our empty error dict.
         */
        $this->_errors = new DForms_Errors_ErrorDict();
        
        /**
         * Only clean bound forms.
         */
        if (!$this->is_bound) {
            return;
        }
        
        /**
         * Set up the cleaned data array.
         */
        $this->cleaned_data = array();
        
        /**
         * Bail early if the form hasn't changed and it's ok to be empty.
         */
        if ($this->empty_permitted and !$this->hasChanged()) {
            return;
        }
        
        /**
         * Clean each field individually.
         */
        foreach ($this->fields as $name => &$field) {
            /**
             * Get the uncleaned, preparsed value from the field widget.
             */
            $value = $field->widget->valueFromData(
                $this->data, 
                $this->files, 
                $this->addPrefix($name)
            );
            
            /**
             * Catch any validation errors for this field.
             */
            try {
                /**
                 * Pass initial data to file fields only
                 */
                if ($field instanceof DForms_Fields_FileField) {
                    /**
                     * Initial data on the form takes precedence over the field.
                     */
                    if (array_key_exists($this->initial, $name)) {
                        $initial = $this->initial[$name];
                    } else {
                        $initial = $field->initial;
                    }
                    
                    /**
                     * Get the file field cleaned value.
                     */
                    $value = $field->clean($value, $initial);
                } else {
                    /**
                     * Get the field cleaned value.
                     */
                    $value = $field->clean($value);
                }
                
                /**
                 * Store the cleaned value for this field.
                 */
                $this->cleaned_data[$name] = $value;
                
                /**
                 * Look for field specific cleaning method on this form class.
                 *
                 * .. note:: These special methods are not in camel case... yet.
                 */
                if (method_exists($this, 'clean_' . $name)) {
                    /**
                     * Field specific clean functions do not recieve a value.
                     */
                    $value = call_user_func(array($this, 'clean_' . $name));
                    
                    /**
                     * Store the new field value.
                     */
                    $this->cleaned_data[$name] = $value;
                }
            } catch (DForms_Errors_ValidationError $e) {
                /**
                 * Create an error object to store the field validation error.
                 */
                $error = new $this->error_class($e->getMessage());
                
                /**
                 * Add the error to the form errors keyed off the field name.
                 */
                $this->_errors->offsetSet($name, $error);
                
                /**
                 * Remove the cleaned data for the errored field.
                 */
                if (array_key_exists($name, $this->cleaned_data)) {
                    unset($this->cleaned_data[$name]);
                }
            }
        }
        unset($field);
        
        /**
         * Run the form's global clean function.
         */
        try {
            /**
             * Store the modified cleaned data.
             */
            $this->cleaned_data = self.clean();
        } catch (DForms_Errors_ValidationError $e) {
            /**
             * Create an error object to store the global validation error.
             */
            $error = new $this->error_class($e->getMessage());
            
            /**
             * Add the error to the form errors with the special global key.
             */
            $this->_errors->offsetSet(self::NON_FIELD_ERRORS, $error);
        }
        
        /**
         * Remove cleaned_data property if there was an error.
         */
        if ($this->_errors->count()) {
            unset($this->cleaned_data);
        }
    }
    
    /**
     * The form specific cleaning method.
     *
     * Form classes may override this method to provide custom form specific
     * validation and cleaning. Throw a validation error if errors are found.
     * All errors thrown in this method will be added to the non-field errors.
     *
     * @throws DForms_Errors_ValidationError
     * @return array
     */
    protected function clean()
    {
        return $this->cleaned_data;
    }
    
    /**
     * Returns the form errors that are not associated with a field.
     *
     * @return object
     */
    protected function nonFieldErrors()
    {
        /**
         * Check for non field errors.
         */
        if ($this->errors->offsetExists(self::NON_FIELD_ERRORS)) {
            /**
             * Return the non field errors.
             */
            return $this->errors->offsetGet(self::NON_FIELD_ERRORS);
        }
        
        /**
         * Return an empty error list object.
         */
        return new $this->error_class;
    }
    
    /**
     * Returns a boolean indicating whether the form's data is not the initial.
     *
     * @return boolean
     */
    protected function hasChanged()
    {
        return (boolean)count($this->changed_data);
    }
    
    /**
     * Returns an array of field names whose data differs from the initial.
     *
     * @return array
     */
    protected function getChangedData()
    {
        /**
         * Check for a cached array of changed data.
         */
        if (is_null($this->_changed_data)) {
            /**
             * Create the cached changed data array.
             */
            $this->_changed_data = array();
            
            /**
             * Check each field for changed data.
             */
            foreach ($this->fields as $name => &$field) {
                /**
                 * Get the prefixed name for the field.
                 */
                $prefixed_name = $this->addPrefix($name);
                
                /**
                 * Get the data as parsed by the field's widget.
                 */
                $data_value = $field->widget->valueFromData(
                    $this->data,
                    $this->files,
                    $prefixed_name
                );
                
                /**
                 * Get the intial field value, handling hidden initial fields.
                 */
                if (!$field->show_hidden_initial) {
                    /**
                     * Determine whether to use the form or field initial data.
                     */
                    if (array_key_exists($name, $this->initial)) {
                        /**
                         * Get the initial value from the form.
                         */
                        $initial_value = $this->initial[$name];
                    } else {
                        /**
                         * Get the initial value from the field.
                         */
                        $initial_value = $field->initial;
                    }
                } else {
                    /**
                     * Get the hidden initial field prefixed name.
                     */
                    $initial_prefixed_name = $this->addInitialPrefix($name);

                    /**
                     * Get the a hidden widget instance for the field.
                     */
                    $hidden_widget = new $field->hidden_widget;
                    
                    /**
                     * Get the initial value from the form initial data.
                     */
                    $initial_value = $hidden_widget->valueFromData(
                        $this->data,
                        $this->files,
                        $initial_prefixed_name
                    );
                }
                
                /**
                 * Determine if the field's widget thinks the data value has 
                 * changed from the initial value.
                 */
                if ($field->widget->hasChanged($initial_value, $data_value)) {
                    /**
                     * Add the changed field name to the cached array.
                     */
                    $this->_changed_data[] = $name;
                }
            }
            unset($field);
        }
        
        /**
         * Return the cached changed data array.
         */
        return $this->_changed_data;
    }
    
    /**
     * Returns an array of bound fields for the form that are hidden.
     *
     * @return array
     */
    protected function hiddenFields() 
    {
        /**
         * Intialize the hidden fields array.
         */
        $hidden_fields = array();
        
        /**
         * Get all the field names for the form.
         */
        $field_names = array_keys($this->fields);
        
        /**
         * Iterate over each field name.
         */
        foreach ($field_names as $name) {
            /**
             * Check a bound field instance of the field to see if it's hidden.
             */
            if ($this->$name->is_hidden) {
                /**
                 * Add the hidden field to the array.
                 */
                $hidden_fields[] = $this->$name;
            }
        }
        
        /**
         * Return the hidden field array.
         */
        return $hidden_fields;
    }
    
    /**
     * Returns an array of bound fields for the form that are *not* hidden.
     *
     * @return array
     */
    protected function visibleFields() 
    {
        /**
         * Intialize the fields array.
         */
        $visible_fields = array();
        
        /**
         * Get all the field names for the form.
         */
        $field_names = array_keys($this->fields);

        /**
         * Iterate over each field name.
         */
        foreach ($field_names as $name) {
            /**
             * Check a bound field instance of the field to see if it's hidden.
             */
            if (!$this->$name->is_hidden) {
                /**
                 * Add the hidden field to the array.
                 */
                $visible_fields[] = $this->$name;
            }
        }
        
        /**
         * Return the hidden field array.
         */
        return $visible_fields;
    }
    
    /**
     * Returns a field name prepended with a prefix provided by the instance.
     *
     * .. note:: This method must remain public so it can be accessed from
     *    bound fields belonging to this form.
     *
     * @param string $field_name The field name to prefix.
     *
     * @return string
     */
    public function addPrefix($field_name)
    {
        /**
         * Check if the prefix is provided.
         */
        if (!is_null($this->prefix)) {
            /**
             * Return the prefixed field name.
             */
            return sprintf('%s-%s', $this->prefix, $field_name);
        }
        
        /**
         * Return the field name sans prefix.
         */
        return $field_name;
    }
    
    /**
     * Returns a prefixed field name suitable for dynamic initial field values.
     *
     * .. note:: This method must remain public so it can be accessed from
     *    bound fields belonging to this form.
     *
     * @param string $field_name The field name to prefix.
     *
     * @return string
     */
    public function addInitialPrefix($field_name)
    {
        /**
         * Return the prefixed field name.
         */
        return sprintf('initial-%s', $this->addPrefix($field_name));
    }
    
    /**
     * Returns a boolean indicating whether a bound form has errors.
     *
     * This method always returns ``false`` for unbound forms.
     *
     * @return boolean
     */
    public function isValid()
    {
        if ($this->is_bound and !$this->errors->count()) {
            return true;
        }
        return false;
    }
    
    /**
     * Returns the raw value as parsed by a field's widget.
     *
     * .. note:: This method must remain public so it can be accessed from
     *    bound fields belonging to this form (i.e. form sets).
     *
     * @return mixed
     */
    public function rawValue($field_name)
    {
        $field = $this->fields[$field_name];
        $prefix = $this->addPrefix($field_name);
        return $field->widget->valueFromData(
            $this->data,
            $this->files,
            $prefix
        );
    }
}