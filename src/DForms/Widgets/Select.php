<?php
/**
 * Select widget
 *
 * This file defines a select widget.
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
 * The base select widget
 *
 * Note that this widget contains a bit of functionality that is more specific
 * to a multiple select widget, but it is easier to inherit that functionality
 * from this class rather than duplicating it.
 *
 * @category   HTML
 * @package    DForms
 * @subpackage Widgets
 * @author     Greg Thornton <xdissent@gmail.com>
 * @copyright  2009 Greg Thornton
 * @license    http://creativecommons.org/licenses/by-sa/3.0/us/
 * @link       http://xdissent.github.com/dforms/
 */
class DForms_Widgets_Select extends DForms_Widgets_Widget
{
    /**
     * An associative array of values and labels for choices.
     *
     * Array keys should be the actual value to be returned by the widget,
     * and the array values should be labels to use when rendering.
     *
     * This must be public so a field can modify choices. Widgets are hardly
     * ever instantiated with constructor arguments, so this is the easiest
     * (and Django) way of solving the problem.
     *
     * @var array
     */
    public $choices;
    
    /**
     * Creates a select widget.
     *
     * @param array $attrs   The attributes to use when rendering the widget.
     * @param array $choices The choices to use for select options.
     *
     * @return null
     */
    public function __construct($attrs=null, $choices=null)
    {
        /**
         * Run default widget constructor.
         */
        parent::__construct($attrs);
        
        /**
         * Initialize choices array if not given.
         */
        if (is_null($choices)) {
            $choices = array();
        }
        
        /**
         * Store widget choices.
         */
        $this->choices = $choices;
    }
    
    /**
     * Renders the select widget to HTML.
     *
     * @param string $name    The name to use for the widget.
     * @param mixed  $value   The value to render into the widget.
     * @param array  $attrs   The attributes to use when rendering the widget.
     * @param array  $choices Additional choices to use for the select.
     *
     * @return string
     */
    public function render($name, $value, $attrs=null, $choices=null)
    {
        /**
         * Protect against null values.
         */
        if (is_null($value)) {
            $value = '';
        }
        
        /**
         * Finalize widget attributes.
         */
        $attrs = $this->buildAttrs(
            array(
                'name' => $name
            ),
            $attrs
        );
        
        /**
         * Initialize output array.
         */
        $ouput = array();
        
        /**
         * Open the select tag.
         */
        $output[] = sprintf(
            '<select%s>',
            DForms_Utils_Attributes::flatten($attrs)
        );
        
        /**
         * Render the options, passing value as an array since only one option
         * can be selected, but `renderOptions()` needs an array of selected
         * options.
         */
        $output[] = $this->renderOptions($choices, array($value));
        
        /**
         * Close the select tag.
         */
        $output[] = '</select>';
        
        return implode("\n", $output);
    }
    
    /**
     * Renders the option tags for a select widget.
     *
     * @param array $choices          Additional select options.
     * @param array $selected_choices The currently selected options.
     *
     * @return string
     */
    protected function renderOptions($choices=null, $selected_choices=null)
    {
        /**
         * Ensure selected choices is an array.
         */
        if (is_null($selected_choices)) {
            $selected_choices = array();
        }
        
        /**
         * Ensure choices is an array.
         */
        if (is_null($choices)) {
            $choices = array();
        }
        
        /**
         * Combine passed choices with the instance's choices.
         *
         * Note: You must do this manually to prevent screwing up the choices
         * if one of the choice arrays has a numeric keyed value. Stupid PHP!
         */
        foreach ($this->choices as $k => $v) {
            if (!array_key_exists($k, $choices)) {
                $choices[$k] = $v;
            }
        }
        
        /**
         * Remove duplicate selections.
         */
        $selected_choices = array_unique($selected_choices);
        
        /**
         * Initialize output array.
         */
        $ouput = array();
        
        /**
         * Render each select option.
         */
        foreach ($choices as $option_value => $option_label) {
            
            /**
             * Check to see if the option is actually an option group.
             */
            if (is_array($option_label)) {
                /**
                 * Open the option group tag.
                 */
                $output[] = sprintf(
                    '<optgroup label="%s">',
                    htmlentities($option_value)
                );
                
                /**
                 * Render each option in the group.
                 */
                foreach ($option_label as $option) {
                    $output[] = $this->renderOption(
                        $option[0],
                        $option[1],
                        $selected_choices
                    );
                }
                
                /**
                 * Close the option group.
                 */
                $output[] = '</optgroup>';
                
            } else {
                /**
                 * Render the option.
                 */
                $output[] = $this->renderOption(
                    $option_value,
                    $option_label,
                    $selected_choices
                );
            }
        }
        
        return implode("\n", $output);
    }
    
    /**
     * Renders an individual option tag for the select widget.
     *
     * @param string $option_value     The option value.
     * @param string $option_label     The option label.
     * @param string $selected_choices The selected choices.
     *
     * @return string
     */
    protected function renderOption($option_value, $option_label, $selected_choices)
    {
        /**
         * Determine if the option should be selected.
         */
        if (in_array($option_value, $selected_choices)) {
            $selected_html = ' selected="selected"';
        } else {
            $selected_html = '';
        }
        
        /**
         * Return the rendered option.
         */
        return sprintf(
            '<option value="%s"%s>%s</option>',
            htmlentities($option_value),
            $selected_html,
            htmlentities($option_label)
        );
    }
}