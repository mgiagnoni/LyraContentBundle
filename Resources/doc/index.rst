LyraContentBundle Documentation
===============================

Note: documentation is still **incomplete**.

Installation
============

Install bundle source code
--------------------------

To install LyraContentBundle and all required dependencies (currently
Gedmo Doctrine Extensions) you can use one of the following alternative
methods.

Vendors script
~~~~~~~~~~~~~~

Add the following lines to the ``deps`` file located in your project root
folder::

    [gedmo-doctrine-extensions]
	    git=git://github.com/l3pp4rd/DoctrineExtensions.git

    [LyraContentBundle]
        git=git://github.com/mgiagnoni/LyraContentBundle.git
        target=bundles/Lyra/ContentBundle

Then cd to your project root folder and run the vendors script::

    ./bin/vendors install

Git submodules
~~~~~~~~~~~~~~

As usual, cd to your project root folder, then run::

    git submodule add git://github.com/l3pp4rd/DoctrineExtensions.git vendor/gedmo-doctrine-extensions
    git submodule add git://github.com/mgiagnoni/LyraContentBundle.git vendor/bundles/Lyra/ContentBundle

Register namespaces
-------------------

``Lyra`` and ``Gedmo`` namespaces must be registered for use by the autoloader::

    // app/autoload.php

    $loader->registerNamespaces(array(
        // other namespaces
        'Gedmo' => __DIR__.'/../vendor/gedmo-doctrine-extensions/lib',
        'Lyra'  => __DIR__.'/../vendor/bundles',
    ));

    // ...

Add bundle to application kernel
--------------------------------

::

    // app/AppKernel.php

    public function registerBundles()
    {
        $bundles = array(
            // other bundles
            new Lyra\ContentBundle\LyraContentBundle(),
        );

    // ...

Publish bundle assets
---------------------

::

    app/console assets:install web

