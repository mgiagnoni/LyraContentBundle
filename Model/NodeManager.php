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

use Gedmo\Sluggable\Util\Urlizer;

abstract class NodeManager implements NodeManagerInterface
{
    public function createNode()
    {
        $class = $this->getClass();
        $node = new $class;

        return $node;
    }

    public function findNodeByPath($path)
    {
        return $this->findNodeBy(array('path' => $path));
    }

    public function findPublishedNodeByPath($path)
    {
        return $this->findNodeBy(array('path' => $path, 'published' => true));
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

    public function updateNode(NodeInterface $node)
    {
        $node->setSlug(Urlizer::transliterate($node->getTitle()));

        if (null === $node->getPath() && $node->getParent()) {
            $node->setPath(ltrim($node->getParent()->getPath().'/'.$node->getSlug(), '/'));
        } else {
            $path = explode('/', $node->getPath());
            $node->setPath(implode('/', array_map('Gedmo\Sluggable\Util\Urlizer::transliterate', $path)));
        }
    }

    public function createItemLink($type, NodeItemInterface $item)
    {
        $node = $item->getNode();
        $node->setItemType($type);
        $node->setItemId($item->getId());
    }
}
