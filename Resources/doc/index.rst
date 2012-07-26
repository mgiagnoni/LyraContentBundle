LyraContentBundle Documentation
===============================

Note: documentation is still **incomplete**.

Installation
============

Install bundle source code
--------------------------

You can use Composer to install LyraContentBundle and all required 
dependencies (currently `Gedmo Doctrine Extensions`_).

Add the following line to the ``composer.json`` file in your project
folder::

    {
        //...

        "require": {
            //...
            "lyra/content-bundle" : "dev-master"
        }

        //...
    }

Get Composer, unless it's already present::

    curl -s http://getcomposer.org/installer | php

Install the bundle with::

    php composer.phar update lyra/content-bundle

.. _Gedmo Doctrine Extensions: https://github.com/l3pp4rd/DoctrineExtensions

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
                    LyraContentBundle: { type: xml }

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

    lyra_content_frontend:
        resource: "@LyraContentBundle/Resources/config/routing/frontend.xml"
        prefix: /cms

Prefix can be omitted, it allows you to manage only a *section* of your
website with the bundle.
Routing configuration file for frontend contains a *catch all* route and
should be imported as the **last entry** of your ``app/config/routing.yml``
file.

Create your Page class
----------------------

The bundle provides an abstract **Page** class as **mappedSuperclass** to allow
easily customization in your application. You need to create a concrete Page
class that will usually reside in a bundle (*AcmePageBundle* in the following
example)::

    <?php

    namespace Acme\PageBundle\Entity;

    use Lyra\ContentBundle\Entity\Page as BasePage;

    use Doctrine\ORM\Mapping as ORM;

    /**
     * @ORM\Entity
     * @ORM\Table(name="page")
     */
    class Page extends BasePage
    {
        /**
         * @ORM\Id
         * @ORM\Column(type="integer")
         * @ORM\GeneratedValue(strategy="AUTO")
         */
        protected $id;
    }

This class must contain at least an **id** property.

Then let the bundle know about your Page class::

    # app/config/config.yml

    lyra_content:
        page:
            model: Acme\PageBundle\Entity\Page

If you need a live example of LyraContentBundle configuration, take a look
at `Lyra CMS repository`_.

The `concrete Page`_ entity is defined in *CMSPageBundle* and extends the
`abstract Page`_ entity defined in *LyraContentBundle* (included in Lyra
CMS repository as git submodule).

.. _Lyra CMS repository: https://github.com/mgiagnoni/LyraCMS
.. _concrete Page: https://github.com/mgiagnoni/LyraCMS/blob/master/src/CMS/PageBundle/Entity/Page.php
.. _abstract Page: https://github.com/mgiagnoni/LyraContentBundle/blob/master/Entity/Page.php

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

Visit::

    http://.../app_dev.php/cms

or, if you haven't specifiedy any route prefix, simply::

    http://.../app_dev.php/

You can create content directly from the home page (link *Create page* on
the right column). Once you have created the first page, try to add a sub-page,
selecting the first page as parent.

Note that the *path* field is auto-generated if left blank when a new page is
created. In any case it can be customized by editing the page.

These are only the basic features. More work is needed.
