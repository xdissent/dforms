<?php

class DForms_Fields_CharField extends DForms_Fields_Field
{
    public function __construct($label=null, $help_text=null, 
        $max_length=null, $min_length=null, $initial=null,
        $required=true, $widget=null, $error_messages=null, 
        $show_hidden_initial=false)
    {
        $this->max_length = $max_length;
        $this->min_length = $min_length;
        
        parent::__construct(
            $label, 
            $help_text, 
            $initial, 
            $required, 
            $widget,
            $error_messages,
            $show_hidden_initial
        )
    }
    
    public function clean($value)
    {
        
    }
}