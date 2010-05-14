<?php

/**
 * Include SimpleTest auto-runner.
 */
require_once 'simpletest/autorun.php';

/**
 * Import DForm.
 */
require_once dirname(__FILE__) . '/../src/DForms/import.php';

class EmptyForm extends DForms_Forms_Form
{
    public static function fields() {
        return array(
            'base1' => new DForms_Fields_CharField('Base 1', 'Help for Base 1', 24),
            'base2' => new DForms_Fields_CharField('Base 2', 'Help for Base 2')
        );
    }
    
    public static function media() {
        return array(
            'js' => array(
                'base.js'
            )
        );
    }
}

class EmptyChildForm extends EmptyForm
{
    public static function fields() {
        return array(
            'base2' => new DForms_Fields_CharField('Overridden Base 2', 'Help for Base 2'),
            'test' => new DForms_Fields_CharField('Test', 'Help for Test', null, null, null, false, 'DForms_Widgets_Textarea')
        );
    }
    
    public static function media() {
        return array(
            'js' => array(
                'test.js'
            )
        );
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $form = new EmptyChildForm($_POST);
    
    if ($form->isValid()) {
        var_dump($form->cleaned_data);
    }
} else {
    $form = new EmptyChildForm();
}
echo "<html><head>\n";
echo $form->media . "\n";
echo "</head><body><form action=\"\" method=\"POST\"><table>\n";
echo $form->html() . "\n";
echo "</table></form></body></html>";