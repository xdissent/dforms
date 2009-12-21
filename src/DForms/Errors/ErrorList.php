<?php

class DForms_Errors_ErrorList extends ArrayObject
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
        
        foreach($this as $error) {
            $output[] = sprintf('<li>%s</li>', $error);
        }
        
        $output[] = '</ul>';
        return implode($output);
    }
}