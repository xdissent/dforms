======
DForms
======

*We found the missing PHP forms library. It was in Python.*

Motivation
----------

Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.


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

* The `@access` and `@static` documentation directives are *never* used since 
  they are redundant when using PHP5 classes.

* Use only long form type names in documentation (i.e. `boolean` instead of 
  `bool`).

* Never use `void` in documentation. Always use `null`.

In the future, a custom `PHP CodeSniffer`_ extension will be available, All code 
contributions *must* pass all tests defined by the extension to be eligible for 
inclusion in the library.

.. _PHP CodeSniffer: http://pear.php.net/package/PHP_CodeSniffer/


The End
-------

Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.