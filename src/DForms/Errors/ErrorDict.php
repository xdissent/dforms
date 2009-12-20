<?php

class DForms_Errors_ErrorDict extends ArrayObject
{
    public function __toString()
    {
        return $this->asUL();
    }
    
    public function asUL()
    {
        if (!$this->count()) {
            return '';
        }
        
        $output = array();
        $output[] = '<ul class="errorlist">';
        
        $iterator = $this->getIterator();
        
        while($iterator->valid()) {
            $output[] = sprintf(
                '<li>%s%s</li>', 
                $iterator->key(),
                $iterator->current()
            );
        }
        
        $output[] = '</ul>';
        return implode($output);
    }
}