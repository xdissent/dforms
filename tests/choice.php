<?php

/**
 * Include SimpleTest auto-runner.
 */
/* require_once 'simpletest/autorun.php'; */

/**
 * Import DForm.
 */
require_once dirname(__FILE__) . '/../src/DForms/import.php';

class DemoForm extends DForms_Forms_Form
{
    public static function fields() {
        $choices = array(
            'asdf' => 'As Dee Eff',
            'cddd' => 'One two three',
            'etphone' => 'ETsts'
        );
        
        return array(
            'base1' => new DForms_Fields_MultipleChoiceField('Base 1', 'Help for Base 1', $choices),
            'base2' => new DForms_Fields_CharField('Base 2', 'Help for Base 2'),
            'bocheck' => new DForms_Fields_BooleanField('bool', 'help bool', null, false),
            'radness' => new DForms_Fields_ChoiceField('Rad', 'Helprad', $choices, null, true, 'DForms_Widgets_RadioSelect')
        );
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $form = new DemoForm($_POST);
    
    if ($form->isValid()) {
        var_dump($form->cleaned_data);
    }
} else {
    $form = new DemoForm();
}
echo "<html><head>\n";
echo $form->media . "\n";
echo "</head><body><form action=\"\" method=\"POST\"><table>\n";
echo $form->html() . "\n";
echo "</table></form></body></html>";