<?php
/**
 * Bound Field
 *
 * This file defines a bound field class.
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
 * The field belonging to a form, plus data.
 *
 * @category   HTML
 * @package    DForms
 * @subpackage Fields
 * @author     Greg Thornton <xdissent@gmail.com>
 * @copyright  2009 Greg Thornton
 * @license    http://creativecommons.org/licenses/by-sa/3.0/us/
 * @link       http://xdissent.github.com/dforms/
 */
class DForms_Fields_BoundField
{
    /**
     * The form to which the field belongs.
     *
     * @var object
     */
    protected $form;
    
    /**
     * The form field the field represents.
     */
    protected $field;
    
    /**
     * The name of the field.
     */
    protected $name;
    
    /**
     * The name of the field used in the HTML.
     */
    protected $html_name;
    protected $html_initial_name;
    protected $html_initial_id;
    protected $help_text;
    protected $label;

    public function __construct($form, $field, $name)
    {
        $this->form = $form;
        $this->field = $field;
        $this->name = $name;
        
        $this->html_name = $form->addPrefix($name);
        
        $this->html_initial_name = $form->addInitialPrefix($name);
        
        $this->html_initial_id = $form->addInitialPrefix($this->auto_id);
        
        if (is_null($field->label)) {
            $this->label = $name;
        } else {
            $this->label = $field->label;
        }
        
        if (is_null($field->help_text)) {
            $this->help_text = '';
        } else {
            $this->help_text = $field->help_text;
        }
    }
    
    public function __get($name)
    {
        /**
         * Dynamic auto id.
         */
        if ($name == 'auto_id') {
            $auto_id = $this->form->auto_id;
            if (!is_null($auto_id) && strpos($auto_id, '%s') !== false) {
                return sprintf($auto_id, $this->html_name);
            } elseif (!is_null($auto_id)) {
                return $this->html_name;
            }
            return '';
        }
        
        /**
         * Dynamic errors.
         */
        if ($name == 'errors') {
            if ($this->form->errors->offsetExists($this->name)) {
                return $this->form->errors[$this->name];
            }
            return new $this->form->error_class;
        }
        
        /**
         * Dynamic data.
         */
        if ($name == 'data') {
            return $this->field->widget->valueFromData(
                $this->form->data,
                $this->form->files,
                $this->html_name
            );
        }
        
        /**
         * Dynamic hidden.
         */
        if ($name == 'is_hidden') {
            return $this->field->widget->is_hidden;
        }
    }
    
    public function __toString()
    {
        if ($this->field->show_hidden_initial) {
            return $this->asWidget() . $this->asHidden(null, true);
        }
        return $this->asWidget();
    }
    
    protected function asWidget($widget=null, $attrs=null, $only_initial=false)
    {
        if (is_null($widget)) {
            $widget = $this->field->widget;
        }
        
        if (is_null($attrs)) {
            $attrs = array();
        }
        
        $auto_id = $this->auto_id;
        
        if (!is_null($auto_id) && !array_key_exists('id', $attrs) 
            && !array_key_exists('id', $widget->attrs)
        ) {
            if (!$only_initial) {
                $attrs['id'] = $auto_id;
            } else {
                $attrs['id'] = $this->html_initial_id;
            }
        }
        
        if (!$this->form->is_bound) {
            if (array_key_exists($this->name, $this->form->initial)) {
                $data = $this->form->initial[$this->name];
            } else {
                $data = $this->field->initial;
            }
        } else {
            if ($this->field instanceof DForms_Fields_FileField 
                && is_null($this->data)
            ) {
                if (array_key_exists($this->name, $this->form->initial)) {
                    $data = $this->form->initial[$this->name];
                } else {
                    $data = $this->field->initial;
                }
            } else {
                $data = $this->data;
            }
        }
        
        if (!$only_initial) {
            $name = $this->html_name;
        } else {
            $name = $this->html_initial_name;
        }
        
        return $widget->render($name, $data, $attrs);
    }
    
    protected function asText($attrs=null, $only_initial=false)
    {
        return $this->asWidget(
            new DForm_Widgets_TextInput,
            $attrs,
            $only_initial
        );
    }
    
    protected function asTextarea($attrs=null, $only_initial=false)
    {
        return $this->asWidget(
            new DForm_Widgets_Textarea,
            $attrs,
            $only_initial
        );
    }

    protected function asHidden($attrs=null, $only_initial=false)
    {
        return $this->asWidget(
            new $this->field->hidden_widget,
            $attrs,
            $only_initial
        );
    }
    
    public function labelTag($contents=null, $attrs=null)
    {
        if (is_null($contents)) {
            $contents = htmlentities($this->label);
        }
        
        $widget = $this->field->widget;
        
        if (array_key_exists('id', $widget->attrs)) {
            $id = $widget->attrs['id'];
        } else {
            $id = $this->auto_id;
        }
        
        /**
         * If the ID is not null or an empty string.
         */
        if ($id) {
            if (is_null($attrs)) {
                $attrs = DForms_Utils_Attributes::flatten($attrs);
            }
            
            $contents = sprintf(
                '<label for="%s"%s>%s</label>',
                $widget->idForLabel($id),
                $attrs,
                $contents
            );
        }
        
        return $contents;
    }
    
    public function cssClasses($extra_classes=null)
    {
        if (is_string($extra_classes)) {
            $extra_classes = preg_split('/\s+/', $extra_classes);
        }
        
        if (!is_array($extra_classes)) {
            $extra_classes = array();
        }
        
        if (count($this->errors) 
            && property_exists($this->form, 'error_css_class')
        ) {
            $extra_classes[] = $this->form->error_css_class;
        }
        
        if ($this->field->required
            && property_exists($this->form, 'required_css_class')
        ) {
            $extra_classes[] = $this->form->required_css_class;
        }
        
        $extra_classes = array_unique($extra_classes);
        
        return trim(implode(' ', $extra_classes));
    }
}