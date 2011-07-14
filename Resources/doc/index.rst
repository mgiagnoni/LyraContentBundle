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

Configure mapping
-----------------

This step is not needed if the configuration parameter ``auto-mapping``
is set to **true** as in default Symfony2 *Standard Edition* configuration::

    # app/config/config.yml

    orm:
        auto_generate_proxy_classes: %kernel.debug%
        entity_managers:
            default:
                mappings:
                    LyraContentBundle: { type: yml }

Enable translator
-----------------

Translator must be always enabled as all messages in templates are *keywords*
while actual text is in translation catalogues::

    # app/config/config.yml

    framework:
        translator: { fallback: en }

Import routing configuration
----------------------------

::

    # app/config/routing.yml

    lyra_content_backend:
        resource: "@LyraContentBundle/Resources/config/routing/backend.yml"
        prefix: /admin

    lyra_content_frontend:
        resource: "@LyraContentBundle/Resources/config/routing/frontend.yml"

Routing configuration file for frontend contains a *catch all* route and
should be imported as the **last entry** of your ``app/config/routing.yml``
file.

Customize base application template
-----------------------------------

LyraContentBundle comes with a **jQuery UI** based backend. All the needed
javascript and css files are included within the *stylesheets* and
*javascripts_head* blocks. Add these blocks to your base application template,
the first is already included if you have based your application on Symfony
*Standard Edition*::

    {# app/Resources/views/base.html.twig #}

    <!DOCTYPE html>
    <html>
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>{% block title %}{% endblock %}</title>
            {% block javascripts_head %}{% endblock %}
            {% block stylesheets %}{% endblock %}
            <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" />
        </head>
    {# ... #}
