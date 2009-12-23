<?php
/**
 * Checkbox input widget
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
 * @subpackage Widgets
 * @author     Greg Thornton <xdissent@gmail.com>
 * @copyright  2009 Greg Thornton
 * @license    http://creativecommons.org/licenses/by-sa/3.0/us/
 * @link       http://xdissent.github.com/dforms/
 */

/**
 * The checkbox input widget.
 *
 * @category   HTML
 * @package    DForms
 * @subpackage Widgets
 * @author     Greg Thornton <xdissent@gmail.com>
 * @copyright  2009 Greg Thornton
 * @license    http://creativecommons.org/licenses/by-sa/3.0/us/
 * @link       http://xdissent.github.com/dforms/
 */
class DForms_Widgets_CheckboxInput extends DForms_Widgets_Widget
{
    /**
     * A callable that will determine if the widget is checked.
     *
     * This member variable may be a callable in the form accepted by 
     * {@link call_user_func()}, or `null` to indicate simple boolean type
     * casting of the widget's value.
     *
     * @var mixed
     */
    protected $check_test;
    
    /**
     * The constructor.
     *
     * @param array $attrs      The attributes to use when rendering the widget.
     * @param mixed $check_test The function to determine whether the widget is
     *                          checked.
     *
     * @return null
     */
    public function __construct($attrs=null, $check_test=null)
    {
        parent::__construct($attrs);
        $this->check_test = $check_test;
    }
    
    /**
     * Renders the widget to HTML.
     *
     * @param string $name    The name to use for the widget.
     * @param mixed  $value   The value to render into the widget.
     * @param array  $attrs   The attributes to use when rendering the widget.
     *
     * @return string
     */
    public function render($name, $value, $attrs=null)
    {
        $attrs = $this->buildAttrs(
            array(
                'type' => 'checkbox',
                'name' => $name
            ),
            $attrs
        );
        
        /**
         * Run the check test if it's a valid function.
         */
        if (is_callable($this->check_test)) {
            $result = $this->check_test($value);
        } else {
            /**
             * Take the boolean type casted value by default.
             */
            $result = (boolean)$value;
        }
        
        /**
         * Add the checked attribute if the widget should be checked.
         */
        if ($result) {
            $attrs['checked'] = 'checked';
        }       
        
        /**
         * Add a value attribute if the value is not a few predefined values.
         */
        if ($value !== '' && $value !== true 
            && $value !== false && !is_null($value)
        ) {
            $attrs['value'] = $value;
        }
        
        /**
         * Return the rendered widget.
         */
        return sprintf(
            '<input%s />',
            DForms_Utils_Attributes::flatten($attrs)
        );
    }
    
    /**
     * Returns the value of this widget determined by the data and name.
     *
     * @return mixed
     */
    public function valueFromData($data, $files, $name)
    {
        if (!array_key_exists($name, $data)) {
            return false;
        }
        
        return parent::valueFromData($data, $files, $name);
    }

    /**
     * Determines whether or not the value of this widget has changed.
     *
     * @param array $initial The initial data array.
     * @param array $data    The data array.
     *
     * @return boolean
     */
    public function hasChanged($initial, $data)
    {
        /**
         * Return true if the boolean value of initial and data differ.
         */
        if ((boolean)$initial != (boolean)$data) {
            return true;
        }
        
        /**
         * Return false by default.
         */
        return false;
    }
}