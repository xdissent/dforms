<?php

abstract class DForms_Widgets_Input extends DForms_Widgets_Widget
{
    protected $input_type;
    
    public function render($name, $value, $attrs=null)
    {
        if (is_null($value)) {
            $value = '';
        }
        
        $attrs = $this->buildAttrs(
            array(
                'type' => $this->input_type,
                'name' => $name
            ),
            $attrs
        );
        
        if ($value != '') {
            $attrs['value'] = $value;
        }
        
        return sprintf('<input%s />', DForms_Utils_Attributes::flatten($attrs));
    }
}