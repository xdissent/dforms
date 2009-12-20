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
     * @var mixed
     */
    protected $widget = 'DForms_Widgets_TextInput';
    
    protected $hidden_widget = 'DForms_Widgets_HiddenInput';
    
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
            $this->label = $label;
        }

        /**
         * Initialize help text.
         */
        if (is_null($help_text)) {
            $this->help_text = $help_text;
        }

        /**
         * Initialize initial data.
         */
        if (is_null($initial)) {
            $this->initial = $initial;
        }
        
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
        
        $this->creation_counter = self::$creation_counter;
        self::$creation_counter += 1;
        
        /**
         * Handle default error messages.
         */
        $this->error_messages = $error_messages;
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
    
    public function clean($value)
    {
        if ($this->required and $this->isEmptyValue($value)) {
            throw new Exception('Validation error');
        }
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
}