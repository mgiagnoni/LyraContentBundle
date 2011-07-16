<?php

/*
 * This file is part of the LyraContentBundle package.
 * 
 * Copyright 2011 Massimo Giagnoni <gimassimo@gmail.com>
 *
 * This source file is subject to the MIT license. Full copyright and license
 * information are in the LICENSE file distributed with this source code.
 */

namespace Lyra\ContentBundle\Entity;

use Lyra\ContentBundle\Model\Node as AbstractNode;

/**
 * Node entity
 */
class Node extends AbstractNode
{
    /**
     * @var integer
     */
    protected $lft;

    /**
     * @var integer
     */
    protected $rgt;

    /**
     * @var integer
     */
    protected $lvl;

    public function getLeft()
    {
        return $this->lft;
    }

    public function getRight()
    {
        return $this->rgt;
    }

    public function getLevel()
    {
        return $this->lvl;
    }

    public function isRoot()
    {
        return 0 === $this->getLevel();
    }

    public function isFirstChild() 
    {
        return !$this->isRoot() && 1 === $this->getLeft() - $this->getParent()->getLeft();
    }

    public function isLastChild()
    {
        return !$this->isRoot() && 1 === $this->getParent()->getRight() - $this->getRight();
    }
    
    public function getDepth()
    {
        return $this->lvl;
    }

    public function __toString()
    {
        return str_repeat('- ', $this->getLevel()) . $this->getTitle();
    }
}
