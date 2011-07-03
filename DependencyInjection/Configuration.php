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

class Configuration implements ConfigurationInterface
{
    protected $bundles;

    public function __construct($bundles)
    {
        $this->bundles = $bundles;
    }

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
                ->arrayNode('types')
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->beforeNormalization()
                            ->ifString()
                            ->then(function($v) { return array('bundle' => $v); })
                        ->end()
                        ->children()
                            ->scalarNode('bundle')
                                ->validate()
                                    ->ifNotInArray(array_keys($bundles))
                                    ->thenInvalid('%s is not a bundle or is not enabled')
                                ->end()
                            ->end()
                            ->arrayNode('form')
                                ->children()
                                    ->scalarNode('name')->end()
                                    ->scalarNode('type')->end()
                                ->end()
                            ->end()
                            ->arrayNode('model')
                                ->children()
                                    ->scalarNode('orm')->end()
                                    ->scalarNode('mongodb')->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                    ->defaultValue(array(
                        'page' => array(
                            'bundle' => 'LyraContentBundle',
                            'form' => array(
                                'name' => 'page',
                                'type' => 'Lyra\\ContentBundle\\Form\\PageFormType'
                            ),
                            'model' => array(
                                'orm' => 'Lyra\\ContentBundle\\Entity\\Page',
                                'mongodb' => 'Lyra\\ContentBundle\\Document\\Page'
                            )
                        )
                    ))
                    ->validate()
                        ->always()
                        ->then(function($v) use ($bundles)
                        {
                            $validated = array();
                            foreach ($v as $type => $params) {

                                $validated[$type] = $params;
                                $nspace = $bundles[$params['bundle']];

                                if (!isset($params['form']['name'])) {
                                    $validated[$type]['form']['name'] = $type;
                                }

                                if (!isset($params['form']['type'])) {
                                    $validated[$type]['form']['type'] = $nspace . '\\Form\\' . ucfirst($type) . 'FormType';
                                }

                                if (!isset($params['model']['orm'])) {
                                    $validated[$type]['model']['orm'] = $nspace . '\\Entity\\' . ucfirst($type);
                                }

                                if (!isset($params['model']['mongodb'])) {
                                    $validated[$type]['model']['mongodb'] = $nspace . '\\Document\\' . ucfirst($type);
                                }
                            }
                            
                            return $validated;
                        })
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
