===============
DForms Overview
===============

---------------------------------------------------------
We found the missing PHP forms library. It was in Python.
---------------------------------------------------------

:Author: Greg Thornton
:Contact: xdissent@gmail.com

.. caution:: This package is a work in progress, and does *not* guarantee any
   useful functionality yet. Your mileage may **seriously** vary.

.. contents::

Motivation
----------

A modern web project's success can hinge on the underlying HTML forms library
upon which it is built. PHP has come a long way since the days of manually
manipulated ``$_POST`` data, but somehow a robust forms library has eluded
the PHP community until now. Sure, you can get by with `HTML_Quickform2`_,
`Zend_Form`_, or (my ol' standby) `Phorms`_, but they all leave something
to be desired. Maybe I've been spoiled - after all, I'm (now) a Python guy and 
have enjoyed some of the *many* decent Python form libraries that are available.
My personal favorite has always been `Django`_'s ``django.forms`` library 
(``django.newforms`` to the old-hat Django guys). It's a dream to work with, 
and has the one modern convenience I haven't found in a PHP library anywhere:
form *media*. Once you've worked with flashy, complex, possibly AJAX'ed forms
in Django, you'll dread returning to the less advanced libraries of PHP. Also,
it's worth mentioning that the concepts used in Django's forms are (imho)
the most logically implemented that I've seen. No doubt, Django's ubiquity
among Python web developers owes a great deal to its forms. After wrestling 
with yet another PHP forms library recently, I realized that it had to be 
done: ``django.forms`` needed a PHP port. Welcome to DForms!

.. _HTML_Quickform2: http://pear.php.net/package/HTML_QuickForm2
.. _Zend_Form: http://framework.zend.com/manual/en/zend.form.html
.. _Phorms: http://www.artfulcode.net/phorms/
.. _Django: http://djangoproject.com


Departures From Django
----------------------

Sometimes PHP is quirky. It doesn't support many of the familiar Python
language constructs to begin with, so porting Python code to PHP can be
a rocky endeavor. Every effort has been made to duplicate the exact behavour
of Django forms, with the following concessions:

* PHP lacks keyword arguments, so to change the value of a parameter near the
  end of the argument list requires a much longer constructor. I'm open for
  suggestions about how to implement keyword arguments in PHP, but every
  solution I've seen so far has required a *lot* of boilerplate, which defeats
  the purpose. In most cases, argument lists have been rearranged to allow
  short method calls for the most common scenarios.

* Method names have been converted to camel case with very few exceptions. 
  Eventually *all* method names should be camel case, but for now it is much
  easier to use the Django names for "special" methods like 
  ``Form::clean_<field name>()``.

* The ``__toString()`` method in PHP *must* not throw or catch *any* exceptions 
  due to a limitation of the PHP engine. Since form validation can sometimes be 
  triggered automatically by outputting the string representation of a form 
  (in Django anyway), the ``__toString()`` method cannot be used to output
  the rendered form in DForms. To get the rendered form, use the ``html()``
  method instead. At some point this could be rectified by pre-rendering and
  caching the html, but that's a less than optimal solution.
  
* There exists no way to define class level member variables with complex types
  like in Python. In cases where this method is used in Django forms (i.e. when
  defining fields), a public static method is provided in DForms to accomplish
  the same task. Simply override the method in the child class to define new 
  class data. See below for an example of defining fields in this manner.

* DForms defines a ``TextField`` class where Django does not. Long constructors
  make overriding widgets just a tad annoying if you have to do it many times,
  so a ``TextField`` class is a ``CharField`` with a ``Textarea`` widget added
  for convenience.
  
* Django allows you to pass a callable as initial data for a field. If found, 
  the initial value will be the value returned by the callable. In PHP, you
  can't store an actual function in a variable. Instead, you may pass initial
  data that is either a string or an array in the form accepted by 
  ``call_user_func()``. Be careful when setting initial values so that you 
  don't accidentally collide with an existing PHP function.


Importing Into Your Project
---------------------------

PHP code often becomes confusing, redundant and possibly erroneous when many
classes or files rely on each other and must be imported, often in a very 
specific order. DForms takes a cue from Python in this regard, and offers an 
"import" script as a single point of entry for the entire library. By importing
DForms *once* in your project, you are assured that all classes are made 
available to your code, without having to remember which class depends on which
file or the correct order of inclusion. Just 
``require_once 'DForms/import.php';`` and you're good to go!

Under the hood, ``import.php`` simply registers the DForms auto loader, which
borrows yet another Pythonic concept: lazy loading. Referencing a DForms class
by name will automatically include the source file and any dependencies for the
requested class. From that point on, the class is available in your code, and
any classes that haven't been used are not even loaded to begin with. The end
result is clean, intuitive and *efficient* code.


Quick Example
-------------

Here's a quick introductory snippet::

    <?php
    
    /**
     * Import DForm.
     */
    require_once 'DForms/import.php';
    
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
            do_something($form->cleaned_data);
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


The Future
----------

There are a few things that still need to be completed:

* *File upload handling* - Since Django's file manipulation classes are obviously
  going to be very different from PHP's, I haven't gotten around to implementing
  file fields in DForms yet. It should be fairly simple and is first priority.
  
* *Debugger* - PHP errors and exceptions are a *real* pain to handle. One 
  unfinished DForms feature is a built in debugger that kicks you to a Django
  style error page when something goes awry. Although off by default, this
  could be overkill.

* *Tests* - I've got a few `SimpleTest`_ test suites for regression testing, but
  they have not been included in the DForms package. This is because I'm 
  reviewing PHP testing options. Any suggestions?

* *More field types* - Right now we're lacking a few field types that Django 
  provides, but they should all be available soon.
  
* *Formsets* - `Django formsets`_ should work as expected when the factory code
  is ported into DForms, but it hasn't *yet*.

* *Demos / Examples* - For the uninitiated, DForms might evoke a "eh, big deal"
  reaction. It would be nice to have some examples showing why Django style
  forms are so great.

* *Documentation site* - DForms is *always* documented inline with extreme
  verbosity using `phpDoc`_ and the rendered docs should be uploaded somewhere.
  
.. _SimpleTest: http://www.simpletest.org
.. _Django formsets: http://docs.djangoproject.com/en/dev/topics/forms/formsets/
.. _phpDoc: http://www.phpdoc.org

I could always use help with the above tasks, so please get in contact if you
have hacking time to spare!

PHP 5.3 contains some REALLY nice features like `late static bindings`_,
`anonymous functions`_, `statically called magic methods`_ and `namespaces`_.
Unfortunately, almost no one has access to version 5.3 in a shared hosting 
environment, which would *seriously* limit the real world usability of DForms.
However, at some point the 5.3 branch will be ubiquitous and we will want to
take advantage of the new features. Specifically, the following changes would
be made:

.. _late static bindings: http://php.net/manual/en/language.oop5.late-static-bindings.php
.. _anonymous functions: http://php.net/manual/en/functions.anonymous.php
.. _statically called magic methods: http://php.net/__callstatic
.. _namespaces: http://php.net/namespaces

* Namespace DForms.

* Allow true anonymous functions as initial data callbacks.

* Simplify field, media, etc. inheritance with late static bindings.

A PHP 5.3 branch of DForms will be created once the time comes to start 
thinking about transitioning. The code for both versions cannot exit
in the same branch no matter how much internal version detection is in place;
the ``static`` keyword in any functional code will cause a fatal (uncatchable) 
error when parsed.


Coding Style
------------

One of DForms' strengths is its meticulously clean code. If you're planning to
contribute code or want to better understand the inner workings of the library,
it's important to be familiar with our coding style, which is a combination of
of those used by `PEAR`_ and `Zend`_. Notable departures include:

.. _PEAR: http://pear.php.net/manual/en/standards.php
.. _Zend: http://framework.zend.com/manual/en/coding-standard.coding-style.html

* PHP files may *never* contain the closing PHP tag at the end of the file.

* Multi-line array declarations should contain *only* one array element per 
  line.

* The ``@access`` and ``@static`` documentation directives are *never* used since 
  they are redundant when using PHP5 classes.

* Use only long form type names in documentation (i.e. ``boolean`` instead of 
  ``bool``).

* Never use `void` in documentation. Always use ``null``.

In the future, a custom `PHP CodeSniffer`_ extension will be available, All code 
contributions *must* pass all tests defined by the extension to be eligible for 
inclusion in the library.

.. _PHP CodeSniffer: http://pear.php.net/package/PHP_CodeSniffer/