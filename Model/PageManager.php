<?php

/*
 * This file is part of the LyraContentBundle package.
 *
 * Copyright 2011 Massimo Giagnoni <gimassimo@gmail.com>
 *
 * This source file is subject to the MIT license. Full copyright and license
 * information are in the LICENSE file distributed with this source code.
 */

namespace Lyra\ContentBundle\Model;

/**
 * Abstract Page Manager implementation.
 */
abstract class PageManager implements PageManagerInterface
{
    /**
     * @var NodeManagerInterface
     */
    protected $nodeManager;

    /**
     * {@inheritdoc}
     */
    public function createPage()
    {
        $class = $this->getClass();
        $page = new $class;

        return $page;
    }

    public function setNodeManager(NodeManagerInterface $manager)
    {
        $this->nodeManager = $manager;
    }

    public function getNodeManager()
    {
        return $this->nodeManager;
    }
}

