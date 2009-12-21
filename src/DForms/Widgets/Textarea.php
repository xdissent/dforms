<?php

class DForms_Widgets_Textarea extends DForms_Widgets_Widget
{
    public function __construct($attrs=null)
    {
        $default_attrs = array(
            'cols' => '40',
            'rows' => '10'
        );
        
        if (!is_null($attrs)) {
            $default_attrs = array_merge($default_attrs, $attrs);
        }

        parent::__construct($default_attrs);
    }
    
    public function render($name, $value, $attrs=null)
    {
        if (is_null($value)) {
            $value = '';
        }
        
        $attrs = $this->buildAttrs(array('name' => $name), $attrs);
        
        return sprintf(
            '<textarea%s>%s</textarea>',
            DForms_Utils_Attributes::flatten($attrs),
            htmlentities($value)
        );
    }
}