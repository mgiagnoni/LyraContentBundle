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
*javascripts_head* blocks, another block (*meta_tags*) is used to insert
meta-description and meta-keywords informations into document header. Add these
blocks to your base application template, the first is already present if you
have based your application on Symfony *Standard Edition*::

    {# app/Resources/views/base.html.twig #}

    <!DOCTYPE html>
    <html>
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>{% block title %}{% endblock %}</title>
            {% block meta_tags %}{% endblock %}
            {% block javascripts_head %}{% endblock %}
            {% block stylesheets %}{% endblock %}
            <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" />
        </head>
    {# ... #}

Update database schema
----------------------

::

    app/console doctrine:schema:update

Create content root node
------------------------

The content tree root node (homepage) is currently created with a console
command::

    app/console lyra:content:init

Finally
-------

As usual do not forget::

    app/console cache:clear

Try it out
==========

Access admin area
-----------------

::

    .../app_dev.php/admin/content

You should see a list of contents (only home page is present). Create a
new page: leave `Home` as *parent* (only choice possible), enter `Page` as
*title*, leave *path* blank, select *published* and enter some content.
Save the page.

Show content in frontend
------------------------

Visit::

    .../app_dev.php/page

You will see the page you have just created. Go back to admin area to create
more content. You can try to add a sub-page, selecting the first page you
have created as parent.

Note that the *path* field is auto-generated if left blank when a new page is
created. In any case it can be customized by editing the page.

These are only the basic features. More work is needed.
