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
 * Interface implemented by page managers.
 */
interface PageManagerInterface
{
    /**
     * Creates an empty page object.
     *
     * @return PageInterface
     */
    function createPage();

    /**
     * Returns a page selected by linked node id.
     *
     * @param mixed $nodeId
     */
    function findPageByNodeId($nodeId);

    /**
     * Persists a page object to the database.
     *
     * @param PageInterface $page
     */
    function savePage(PageInterface $page);
}

