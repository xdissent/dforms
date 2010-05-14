<?php

/**
 * Import DForm.
 */
require_once dirname(__FILE__) . '/../src/DForms/import.php';

/**
 * Define a simple form.
 */
class DemoForm extends DForms_Forms_Form
{
    /**
     * Declare some fields.
     */
    public static function fields() {
        return array(
            'first_name' => new DForms_Fields_CharField(
                'First Name',
                'Enter your first name.'
            ),
            'last_name' => new DForms_Fields_CharField(
                'Last Name',
                'Enter your last name.'
            )
        );
    }
    
    /**
     * Declare some form media.
     */
    public static function media() {
        return array(
            'js' => array(
                'demo.js'
            ),
            'css' => array(
                array(
                    'screen' => 'demo.css',
                    'print' => 'print.css'
                )
            )
        );
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    /**
     * Bind the form to the POST data.
     */
    $form = new DemoForm($_POST);
    
    if ($form->isValid()) {
        /**
         * Do something with the form data.
         */
        // do_something($form->cleaned_data);
        echo 'Valid';
    }
    
} else {
    /**
     * Instantiate an unbound form.
     */
    $form = new DemoForm();
}

?>
<html>
    <head>
    <?= $form->media ?>
    </head>
    <body>
        <form action="" method="POST">
            <table>
                <?= $form->html() ?>
                <tr>
                    <td colspan="2">
                        <input type="submit" />
                    </td>
                </tr>
            </table>
        </form>
    </body>
</html>