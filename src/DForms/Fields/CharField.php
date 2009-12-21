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
        );
    }
    
    public function clean($value)
    {
        /**
         * Call the inherited clean method.
         */
        parent::clean($value);
        
        /**
         * Make sure we don't have an empty value.
         */
        if ($this->isEmptyValue($value)) {
            $value = '';
        }
        
        $value_length = strlen($value_length);
        
        if (!is_null($this->max_length) && $value_length > $this->max_length) {
            throw new DForms_Errors_ValidationError(
                sprintf(
                    $this->error_messages['max_length'],
                    $this->max_length, 
                    $value_length
                )
            );
        }
        
        if (!is_null($this->min_length) && $value_length < $this->min_length) {
            throw new DForms_Errors_ValidationError(
                sprintf(
                    $this->error_messages['min_length'],
                    $this->min_length, 
                    $value_length
                )
            );
        }
    }
    
    public static function errorMessages() {
        return array(
            'max_length' => 'Ensure this value has at most %1$d characters (it has %2$d).',
            'min_length' => 'Ensure this value has at least %1$d characters (it has %2$d).',
        );
    }
}