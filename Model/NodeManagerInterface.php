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

    function findPublishedNodeByPath($path);

    /**
     * Returns a node selected by primary key.
     *
     * @param integer $nodeId
     */
    function findNode($nodeId);

    /**
     * Returns a node selected by given criteria.
     *
     * @param array $criteria
     */
    function findNodeBy(array $criteria);

    /**
     * Returns all nodes ordered as tree.
     */
    function findAllNodes();

    /**
     * Returns the Query Builder to select all nodes ordered as tree.
     */
    function getNodeTreeQueryBuilder();

    /**
     * Returns all ascendants of a given node.
     *
     * @param NodeInterface $node
     */
    function findNodeAscendants(NodeInterface $node);

    /**
     * Returns the Query Builder to select node ascendants.
     */
    function getNodeAscendantsQueryBuilder(NodeInterface $node);

    /**
     * Returns all descendants of a given node.
     *
     * @param NodeInterface $node
     */
    function findNodeDescendants(NodeInterface $node);

    /**
     * Returns the Query Builder to select node descendants.
     */
    function getNodeDescendantsQueryBuilder(NodeInterface $node);

    /**
     * Returns all nodes that are *not* descendants of a given node.
     *
     * @param NodeInterface $node
     */
    function findNodeNotDescendants(NodeInterface $node);

    /**
     * Returns the Query Builder to select all nodes that are *not*
     * descendants of a given node.
     *
     * @param NodeInterface $node
     */
    function getNodeNotDescendantsQueryBuilder(NodeInterface $node);

    /**
     * Returns all ascendants of a given node selected by path.
     *
     * @param string $path node path
     */
    function findPathNodes($path);

    /**
     * Returns all published ascendants of a given node selected by path.
     *
     * @param string $path node path
     */
    function findPublishedPathNodes($path);

    /**
     * Returns a content item linked to a given node.
     *
     * @param NodeInterface $node
     */
    function findNodeContent(NodeInterface $node);

    /**
     * Moves a given node up within its siblings.
     *
     * @param NodeInterface node
     * @param integer $step how many positions to move
     */
    function moveNodeUp(NodeInterface $node, $step);

    /**
     * Moves a given node down within its siblings.
     *
     * @param NodeInterface node
     * @param integer $step how many positions to move
     */
    function moveNodeDown(NodeInterface $node, $step);

    /**
     * Publishes a given node.
     *
     * @param NodeInterface $node
     */
    function publishNode(NodeInterface $node);

    /**
     * Unpublishes a given node.
     *
     * @param NodeInterface $node
     */
    function unpublishNode(NodeInterface $node);

    /**
     * Persists a given node to the database.
     *
     * @param NodeInterface $node
     */
    function saveNode(NodeInterface $node);

    /**
     * Removes a given node from the database.
     *
     * @param NodeInterface $node
     */
    function removeNode(NodeInterface $node);

    /**
     * Returns the name of concrete class (entity/document).
     */
    function getClass();
}

