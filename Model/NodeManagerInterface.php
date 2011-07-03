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

interface NodeManagerInterface
{
    function findNodeByPath($path);

    function findNode($nodeId);

    function findNodeBy(array $criteria);

    function findAllNodes();

    function getNodeTreeQueryBuilder();

    function findNodeAscendants(NodeInterface $node);

    function getNodeAscendantsQueryBuilder(NodeInterface $node);

    function findNodeDescendants(NodeInterface $node);

    function getNodeDescendantsQueryBuilder(NodeInterface $node);

    function findNodeNotDescendants(NodeInterface $node);

    function getNodeNotDescendantsQueryBuilder(NodeInterface $node);

    function findPathNodes($path);

    function findNodeContent(NodeInterface $node);

    function moveNodeUp(NodeInterface $node, $step);

    function moveNodeDown(NodeInterface $node, $step);

    function publishNode(NodeInterface $node);

    function unpublishNode(NodeInterface $node);

    function saveNode(NodeInterface $node);

    function removeNode(NodeInterface $node);

    function getClass();
}

