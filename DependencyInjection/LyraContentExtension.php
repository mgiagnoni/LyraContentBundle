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

        $container->setAlias('lyra_content.node_manager', $config['service']['node_manager']);
        $container->setAlias('lyra_content.page_manager', $config['service']['page_manager']);

        $types = $config['types'];

        foreach($config['types'] as $name => $value) {
            $container->setParameter(sprintf('lyra_content.%s.bundle', $name), $value['bundle']);
            $container->setParameter(sprintf('lyra_content.%s.form.type.class', $name), $value['form']['type']);
            $container->setParameter(sprintf('lyra_content.%s.form.name', $name), $value['form']['name']);
            $model = $value['model'][$config['db_driver']];
            $container->setParameter(sprintf('lyra_content.model.%s.class', $name), $model);
            $types[$name]['model'] = $model;
        }

         $container->setParameter('lyra_content.types', $types);
    }
}
