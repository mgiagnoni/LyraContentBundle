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

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Bundle configuration.
 */
class Configuration implements ConfigurationInterface
{
    protected $bundles;

    public function __construct($bundles)
    {
        $this->bundles = $bundles;
    }

    /**
     * Generates the configuration tree.
     *
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $bundles = $this->bundles;
        $treeBuilder = new TreeBuilder();

        $treeBuilder->root('lyra_content', 'array')
            ->children()
                ->scalarNode('db_driver')
                    ->cannotBeEmpty()
                    ->defaultValue('orm')
                    ->validate()
                        ->ifNotInArray(array('orm', 'mongodb'))
                        ->thenInvalid('The %s database driver is not supported')
                    ->end()
                ->end()
                ->arrayNode('page')
                    ->isRequired()
                    ->children()
                        ->scalarNode('model')->isRequired()->cannotBeEmpty()->end()
                        ->arrayNode('form')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('type')->defaultValue('lyra_content_page')->end()
                                ->scalarNode('name')->defaultValue('lyra_content_page_form')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                //
                ->arrayNode('types')
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('bundle')
                                ->isRequired()
                                ->validate()
                                    ->ifNotInArray(array_keys($bundles))
                                    ->thenInvalid('%s is not a bundle or is not enabled')
                                ->end()
                            ->end()
                            ->scalarNode('model')->isRequired()->cannotBeEmpty()->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('service')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('node_manager')->defaultValue('lyra_content.node_manager.default')->end()
                        ->scalarNode('page_manager')->defaultValue('lyra_content.page_manager.default')->end()
                    ->end()
                ->end()
            ->end()
        ->end();

        return $treeBuilder;
    }
}
