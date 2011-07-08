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

abstract class NodeManager implements NodeManagerInterface
{
    public function findNodeByPath($path)
    {
        return $this->findNodeBy(array('path' => $path));
    }

    public function publishNode(NodeInterface $node)
    {
        if (!$node->isPublished()) {
            $node->setPublished(true);
            $this->saveNode($node);
        }
    }

    public function unpublishNode(NodeInterface $node)
    {
        if ($node->isPublished()) {
            $node->setPublished(false);
            $this->saveNode($node);
        }
    }

    public function normalizeNode(NodeInterface $node)
    {
        $path = explode('/', $node->getPath());
        $node->setPath(implode('/', array_map('Gedmo\Sluggable\Util\Urlizer::transliterate', $path)));
    }
}
