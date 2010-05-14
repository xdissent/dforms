<?php

function pre_ob($ob) {
    $output = array('<html><body><div><pre>');
    
    $output[] = htmlentities($ob);
    
    $output[] = '</pre></div></body></html>';
    return implode("\n", $output);
}

ob_start('pre_ob');

/**
 * Import DForm.
 */
require_once dirname(__FILE__) . '/../src/DForms/import.php';

$w = new DForms_Widgets_RadioSelect();

$choices = array(
    array('J', 'John'),
    array('P', 'Paul'),
    array('G', 'George'),
    array('R', 'Ringo')
);

echo $w->render('beatle', 'J', null, $choices);

echo $w->render('beatle', null, null, $choices);

echo $w->render('beatle', 'John', null, $choices);

$choices = array(
    '1' => '1',
    '2' => '2',
    '3' => '3'
);

echo $w->render('num', 2, null, $choices);

$choices = array(
    1 => 1,
    2 => 2,
    1 => 3
);

echo $w->render('num', '2', null, $choices);

echo $w->render('num', 2, null, $choices);


//print w.render('beatle', 'J', choices=(('J', 'John'), ('P', 'Paul'), ('G', 'George'), ('R', 'Ringo')))