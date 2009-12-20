    <?php
/**
 * Field
 *
 * This file defines the base field class.
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
 * @subpackage Fields
 * @author     Greg Thornton <xdissent@gmail.com>
 * @copyright  2009 Greg Thornton
 * @license    http://creativecommons.org/licenses/by-sa/3.0/us/
 * @link       http://xdissent.github.com/dforms/
 */

/**
 * The DForms field base class.
 *
 * @category   HTML
 * @package    DForms
 * @subpackage Fields
 * @author     Greg Thornton <xdissent@gmail.com>
 * @copyright  2009 Greg Thornton
 * @license    http://creativecommons.org/licenses/by-sa/3.0/us/
 * @link       http://xdissent.github.com/dforms/
 */
abstract class DForms_Fields_Field
{
    /**
     * The field label.
     *
     * Each field should have a label string, which is often displayed with
     * the field's widget when a form is rendered. Field subclasses can specify 
     * a default label by redeclaring this member variable with a value. Keep
     * in mind that when instantiating a field, a label may also be specified,
     * which overrides this class-wide default. Pass ``null`` to invoke the
     * default value.
     *
     * @var string
     */
    protected $label;
    
    /**
     * The field help text.
     *
     * A field can define a help string to provide the end user with info about
     * how to use the field properly. This is particularly useful in more
     * complex fields and is *highly* recommended. A form can always choose to
     * ignore its fields' help texts, so it's best to define it in all cases
     * for compatibility.
     *
     * @var string
     */
    protected $help_text;
    
    /**
     * The field initial value.
     *
     * Each field class may redefine this default initial value, which is used
     * in a field if the containing form's data has no value for this field.
     * The initial value may be overridden when instantiating a field, or
     * when instantiating a form, in that order. Passing ``null`` in either
     * case will invoke the field class level default value.
     *
     * @var mixed
     */
    protected $initial;
    
    /**
     * A flag indicating that a field is required in a form.
     *
     * To indicate that a field should not be required, pass ``false`` when
     * instantiating the field.
     *
     * By default, a form will be considered invalid if its data doesn't contain
     * a value for each and every field. You may override this behaviour on a
     * field by field basis when you instantiate the fields for a form.
     *
     * @var boolean
     */
    protected $required;
    
    /**
     * The field widget.
     *
     * Fields use a widget to handle the rendering of its value into markup.
     * Field classes may override the type of widget to use by redefining
     * this member variable with a string representing the widget class name.
     * They may also set the value of this member variable to either a widget
     * class name *or* a widget instance before calling the default constructor
     * to override behaviour on a per-instance level. Of course, a class or
     * instance may be specified when instantiating a field, or pass ``null``
     * to use the default widget as specified in either the constructor or
     * field class declaration.
     *
     * .. note:: This member is public to facilitate access to the widget's
     *    methods and properties by things like forms. That's not so bad
     *    because after the field is initialized, we're guaranteed to have
     *    an actual widget instance.
     *
     * @var mixed
     */
    public $widget = 'DForms_Widgets_TextInput';
    
    protected $hidden_widget = 'DForms_Widgets_HiddenInput';
    
    /**
     * The field error messages.
     *
     * Invalid fields will throw validation exceptions, which are usually 
     * caught by forms. Often, a message should be displayed indicating 
     * exactly what went wrong. The error messages member is an associative
     * array consisting of error names (as keys) and messages (as values).
     * Field classes may override the ``errorMessages`` static method to
     * provide defaults. It's important to remember that field validation
     * is almost always inherited rather than completely overridden. For
     * this reason, subclasses will always inherit a parent class's 
     * error messages, which are then updated, rather than replaced, by
     * those defined in the child class's ``errorMessages`` method. Field
     * instances may also provide additional messages, or override parent
     * messages when instantiated.
     *
     * @var array
     */
    protected $error_messages;
    
    protected $show_hidden_initial;
    
    /**
     * The creation counter for all field instances.
     *
     * It can be difficult to keep track of the order in which fields should
     * be presented when rendering a form. This counter is used to ensure
     * all fields are rendered in the correct order. Each time a field is 
     * instantiated, this counter is saved to an instance member variable
     * and then incremented statically.
     *
     * var integer
     */
    protected static $creation_counter = 0;
    
    /**
     * We should really be declaring a non-static member to hold the per-
     * instance creation counter (below), but PHP doesn't like you to use
     * the same name for static and instance member variables. It works; just
     * declare the instance member variable at runtime.
     */
    //protected $creation_counter;

    /**
     * Instantiates a field.
     *
     * Instances that wish to override the widget with a widget *instance*
     * should do so in their constructor *before* calling this constructor.
     *
     * @param mixed   $label     The label to display for the field. Pass null
     *                           for the class default.
     * @param mixed   $help_text The help text to display for the field. Pass 
     *                           null for the class default.
     * @param mixed   $initial   The initial value to use for the field. Pass
     *                           null for the class default.
     * @param boolean $required  A flag indicating whether a field is required.
     * @param mixed   $widget    The class name or instance of the widget for
     *                           the field.
     */
    public function __construct($label=null, $help_text=null, $initial=null,
        $required=true, $widget=null, $error_messages=null, 
        $show_hidden_initial=false
    ) {
        /**
         * Initialize label.
         */
        if (is_null($label)) {
            $label = $this->label;
        }
        $this->label = $label;

        /**
         * Initialize help text.
         */
        if (is_null($help_text)) {
            $help_text = $this->help_text;
        }
        $this->help_text = $help_text;

        /**
         * Initialize initial data.
         */
        if (is_null($initial)) {
            $initial = $this->initial;
        }
        $this->initial = $initial;
        
        /**
         * Save our required flag.
         */
        $this->required = $required;
        
        /**
         * Provide a default widget.
         */
        if (is_null($widget)) {
            $widget = $this->widget;
        }
        
        /**
         * If passed a widget class name, instantiate it.
         */
        if (is_string($widget)) {
            $widget = new $widget;
        }
        
        /**
         * Set extra widget attrs defined by the field.
         */
        $extra_attrs = $this->widgetAttrs($widget);
        $widget->attrs = array_merge($widget->attrs, $extra_attrs);
        
        /**
         * Store the widget instance.
         */
        $this->widget = $widget;
        
        /**
         * Save the current creation counter for this instance.
         */
        $this->creation_counter = self::$creation_counter;
        
        /**
         * Update the creation counter to indicate a new field instance.
         */
        self::$creation_counter += 1;
        
        /**
         * Handle default error messages.
         */
        $this->error_messages = $this->getErrorMessages();
        
        if (!is_null($error_messages)) {
            $this->error_messages = array_merge(
                $this->error_messages,
                $error_messages
            );
        }
    }
    
    /**
     * Determine if the given value is actually empty.
     *
     * Only ``null`` and the empty string, ``''`` are considered "empty". This
     * allows for things like boolean fields which need to receive ``false`` or
     * number fields that need to receive ``0``.
     *
     * @param mixed $value The value to check for empty.
     *
     * @return boolean
     */
    public function isEmptyValue($value)
    {
        /**
         * Check for a known empty value.
         */
        if (is_null($value) || $value === '') {
            return true;
        }
        
        /**
         * Everything else is considered non-empty.
         */
        return false;
    }
    
    /**
     * Validates the given value and returns a cleaned (valid) value.
     *
     * @param mixed $value The value to clean.
     *
     * @throws DForms_Exceptions_ValidationError
     * @return mixed
     */
    public function clean($value)
    {
        /**
         * Check to see if the field is required.
         */
        if ($this->required and $this->isEmptyValue($value)) {
            /**
             * Throw a validation error indicating the field value is missing.
             */
            throw new DForms_Exceptions_ValidationError(
                $this->error_messages['required']
            );
        }
        
        /**
         * Return the cleaned value.
         */
        return $value;
    }
    
    /**
     * Returns the attributes for this field that should be added to the widget.
     *
     * Field classes may override this method to return custom attributes. The
     * returned value should be an associative array of attributes.
     *
     * @param object $widget The widget instance that will recieve the 
     *                       returned attributes.
     *
     * @return array
     */
    public function widgetAttrs($widget)
    {
        return array();
    }
    
    public function getErrorMessages($class=null) {
        /**
         * Determine the class name of the field.
         */
        if (is_null($class)) {
            $class = get_class($this);
        }
        
        /**
         * Get the fields declared on the field.
         */
        $error_messages = call_user_func(array($class, 'errorMessages'));
        
        /**
         * Determine the parent class of the field.
         */
        $parent = get_parent_class($class);

        /**
         * Bail early if we're dealing with the base field class.
         */
        if ($class == __CLASS__) {
            return $error_messages;
        }
        
        /**
         * Recurse and merge parent messages into this form's error messages.
         */
        $error_messages = array_merge(
            $this->getErrorMessages($parent),
            $error_messages
        );
        
        /**
         * Return the merged error messages.
         */
        return $error_messages;
    }
    
    /**
     * Returns the error messages to use by default for the field.
     */
    public static function errorMessages() {
        return array(
            'required' => 'This field is required.',
            'invalid' => 'Enter a valid value.'
        );
    }
}