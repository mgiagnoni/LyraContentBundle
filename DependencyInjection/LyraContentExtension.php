<?php

/*
 * This file is part of the LyraContentBundle package.
 *
 * Copyright 2011 Massimo Giagnoni <gimassimo@gmail.com>
 *
 * This source file is subject to the MIT license. Full copyright and license
 * information are in the LICENSE file distributed with this source code.
 */

namespace Lyra\ContentBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;

/**
 * Configures the Dependency Injection container.
 */
class LyraContentExtension extends Extension
{
    /**
     * Loads and processes configuration files.
     *
     * @param array $configs
     * @param ContainerBuilder $container
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $bundles = array();

        foreach ($container->getParameter('kernel.bundles') as $name => $nspace) {
            $p = strrpos($nspace, '\\');
            $bundles[$name] = substr($nspace, 0, $p);
        }

        $processor = new Processor();
        $configuration = new Configuration($bundles);

        $config = $processor->processConfiguration($configuration, $configs);

        //Hardcoded as MongoDB is not supported yet
        $config['db_driver'] = 'orm';

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        foreach (array($config['db_driver'], 'form', 'listeners') as $basename) {
            $loader->load(sprintf('%s.xml', $basename));
        }

        $container->setParameter('lyra_content.model.page.class', $config['page']['model']);
        $container->setParameter('lyra_content.page.form.type', $config['page']['form']['type']);
        $container->setParameter('lyra_content.page.form.name', $config['page']['form']['name']);

        $container->setAlias('lyra_content.node_manager', $config['service']['node_manager']);
        $container->setAlias('lyra_content.page_manager', $config['service']['page_manager']);

        $types = array_merge(array('page' => array(
            'bundle' => 'LyraContentBundle',
            'model' => $config['page']['model']
        )), $config['types']);

        $container->setParameter('lyra_content.types', $types);
    }
}
