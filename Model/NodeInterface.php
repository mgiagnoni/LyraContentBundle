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

interface NodeInterface
{
    function getId();

    function getTitle();

    function setTitle($title);

    function getLinkTitle();

    function setLinkTitle($linkTitle);

    function getSlug();

    function setSlug($slug);

    function getPath();

    function setPath($path);

    function isPublished();

    function setPublished($published);

    function getType();

    function setType($type);

    function getParent();

    function setParent(NodeInterface $node);

    function getChildren();

    function updatePath();

    function getDepth();

    function isRoot();

    function isFirstChild();

    function isLastChild();
}
