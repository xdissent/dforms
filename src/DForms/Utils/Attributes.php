<?php

class DForms_Utils_Attributes
{
    public static function flatten($attrs) {
        $flat = '';
        
        if (!is_array($attrs)) {
            return $flat;
        }
        
        foreach ($attrs as $name => $value) {
            $flat .= sprintf(' %s="%s"', $name, htmlentities($value));
        }
        return $flat;
    }
}