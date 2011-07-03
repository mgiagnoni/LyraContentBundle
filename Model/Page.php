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

use Lyra\ContentBundle\Model\NodeInterface;

abstract class Page implements PageInterface, NodeItemInterface
{
    protected $id;

    protected $content;

    protected $metaTitle;

    protected $metaDescription;
    
    protected $metaKeywords;

    protected $node;

    public function getId()
    {
        return $this->id;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getMetaTitle()
    {
        return $this->metaTitle;
    }

    public function setMetaTitle($metaTitle)
    {
        $this->metaTitle = $metaTitle;
    }
    
    public function getMetaDescription()
    {
        return $this->metaDescription;
    }

    public function setMetaDescription($metaDescription) 
    {
        $this->metaDescription = $metaDescription;
    }

    public function getMetaKeywords()
    {
        return $this->metaKeywords;
    }

    public function setMetaKeywords($metaKeywords)
    {
        $this->metaKeywords = $metaKeywords;
    }

    public function getNode()
    {
        return $this->node;
    }

    public function setNode(NodeInterface $node)
    {
        $this->node = $node;
    }
}
