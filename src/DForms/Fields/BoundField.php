<?php

class DForms_Fields_BoundField
{
    protected $form;
    protected $field;
    protected $name;
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
        
        $this->html_name = $form->add_prefix($name);
        
        $this->html_initial_name = $form->add_initial_prefix($name);
        
        $this->html_initial_id = $form->add_initial_prefix($form->auto_id);
        
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
                return $this->form->errors->offsetGet($this->name);
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
}