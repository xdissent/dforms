<?php

class DForms_Widgets_RadioFieldRenderer implements ArrayAccess, Iterator, Countable
{    
    public function __construct($name, $value, $attrs, $choices)
    {
        $this->name = $name;
        $this->value = $value;
        $this->attrs = $attrs;
        $this->choices = $choices;
    }
    
    public function __toString()
    {
        return $this->render();
    }
    
    public function offsetSet($offset, $value)
    {
        throw new Exception('Cannot add choices to radio field renderer.');
    }
    
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->choices);
    }
    
    public function offsetUnset($offset)
    {
        throw new Exception('Cannot remove choices from radio field renderer.');
    }
    
    public function offsetGet($offset)
    {
        if (!array_key_exists($offset, $this->choices)) {
            return null;
        }
        
        return new DForms_Widgets_RadioInput(
            $this->name,
            $this->value,
            $this->attrs,
            $this->choices[$offset],
            $offset
        );
    }
    
    public function rewind()
    {
        reset($this->choices);
    }
    
    public function current()
    {
        return $this->offsetGet($this->key());
    }
    
    public function key()
    {
        return key($this->choices);
    }
    
    public function next()
    {
        next($this->choices);
        return $this->current();
    }
    
    public function valid()
    {
        if ($this->key() !== null) {
            return true;
        }
        return false;
    }
    
    public function count()
    {
        return count($this->choices);
    }
    
    public function render()
    {
        $output = array('<ul>');
        
        foreach ($this as $radio) {
            $output[] = sprintf(
                '<li>%s</li>',
                $radio
            );
        }
        
        $output[] = '</ul>';
        
        return implode("\n", $output);
    }
}