<?php

namespace DForms\Widgets;

use DForms\Utils\Attributes;

class RadioInput
{
    protected $name;
    protected $value;
    protected $attrs;
    protected $choice_value;
    protected $choice_label;
    protected $index;
    
    public function __construct($name, $value, $attrs, $choice, $index)
    {
        $this->name = $name;
        $this->value = $value;
        $this->attrs = $attrs;
        $this->choice_value = $choice[0];
        $this->choice_label = $choice[1];
        $this->index = $index;
    }
    
    public function __toString()
    {
        if (array_key_exists('id', $this->attrs)) {
            $label_for = sprintf(
                ' for="%s_%s"',
                $this->attrs['id'],
                $this->index
            );
        } else {
            $label_for = '';
        }
        
        $choice_label = htmlentities($this->choice_label);
        
        return sprintf(
            '<label%s>%s %s</label>',
            $label_for,
            $this->tag(),
            $choice_label
        );
    }
    
    public function isChecked()
    {
        if ($this->value == $this->choice_value) {
            return true;
        }
        return false;
    }
    
    public function tag()
    {
        if (array_key_exists('id', $this->attrs)) {
            $this->attrs['id'] = sprintf(
                '%s_%s',
                $this->attrs['id'],
                $this->index
            );
        }
        
        $attrs = array(
            'type' => 'radio',
            'name' => $this->name,
            'value' => $this->choice_value
        );
        $attrs = array_merge($this->attrs, $attrs);
        
        if ($this->isChecked()) {
            $attrs['checked'] = 'checked';
        }
        
        return sprintf(
            '<input%s />',
            Attributes::flatten($attrs)
        );
    }
}