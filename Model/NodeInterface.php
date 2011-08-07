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
    /**
     * Gets node id.
     *
     * @return mixed
     */
    function getId();

    /**
     * Gets node title.
     *
     * @return string
     */
    function getTitle();

    /**
     * Sets node title.
     *
     * @param string $title
     */
    function setTitle($title);

    /**
     * Gets node short title (for links).
     *
     * @return string
     */
    function getLinkTitle();

    /**
     * Sets node short title.
     *
     * @param string $linkTitle
     */
    function setLinkTitle($linkTitle);

    /**
     * Gets node slug.
     *
     * @return string
     */
    function getSlug();

    /**
     * Sets node slug.
     *
     * @param string $slug
     */
    function setSlug($slug);

    /**
     * Gets node path.
     *
     * @return string
     */
    function getPath();

    /**
     * Sets node path.
     *
     * @param string $path
     */
    function setPath($path);

    /**
     * Gets node published status.
     *
     * @return Boolean
     */
    function isPublished();

    /**
     * Sets node published status.
     *
     * @param Boolean $published
     */
    function setPublished($published);

    /**
     * Gets node item type.
     *
     * @return string
     */
    function getItemType();

    /**
     * Sets node item type.
     *
     * @param string $type
     */
    function setItemType($type);

    /**
     * Gets node item id
     *
     * @return mixed
     */
    function getItemId();

    /**
     * Sets node item id
     *
     * @param mixed $itemId
     */
    function setItemId($itemId);

    /**
     * Gets node parent.
     *
     * @return NodeInterface
     */
    function getParent();

    /**
     * Sets node parent.
     *
     * @param NodeInterface $node
     */
    function setParent(NodeInterface $node);

    /**
     * Gets node children.
     *
     * @return Doctrine\Common\Collections\ArrayCollection
     */
    function getChildren();

    /**
     * Gets node depth (level).
     *
     * @return integer
     */
    function getDepth();

    /**
     * Checks whether node is root node.
     *
     * @return Boolean
     */
    function isRoot();

    /**
     * Checks whether node is the first child of its parent.
     *
     * @return Boolean
     */
    function isFirstChild();

    /**
     * Checks whether node is the last child of its parent.
     *
     * @return Boolean
     */
    function isLastChild();
}
