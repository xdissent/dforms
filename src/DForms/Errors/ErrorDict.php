<?php

class DForms_Errors_ErrorDict extends ArrayObject
{
    public function __toString()
    {
        return $this->asUL();
    }
    
    public function asUL()
    {
        if (!count($this)) {
            return '';
        }
        
        $output = array();
        $output[] = '<ul class="errorlist">';
        
        foreach ($this as $key => $val) {
            $output[] = sprintf('<li>%s%s</li>', $key, $val);
        }
        
        $output[] = '</ul>';
        return implode($output);
    }
}