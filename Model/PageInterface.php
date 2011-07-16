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

interface PageInterface
{
    /**
     * Gets page unique id.
     *
     * @return mixed
     */
    function getId();

    /**
     * Gets page content.
     *
     * @return string
     */
    function getContent();

    /**
     * Sets page content.
     *
     * @param string $content
     */
    function setContent($content);

    /**
     * Gets page meta-title.
     *
     * @return string
     */
    function getMetaTitle();

    /**
     * Sets page meta-title.
     *
     * @param string $metaTitle
     */
    function setMetaTitle($metaTitle);

    /**
     * Gets page meta-description.
     *
     * @return string
     */
    function getMetaDescription();

    /**
     * Sets page meta-description.
     *
     * @param string $metaDescription
     */
    function setMetaDescription($metaDescription);

    /**
     * Gets page meta-keywords.
     *
     * @return string
     */
    function getMetaKeywords();

    /**
     * Sets page meta-keywords.
     *
     * @param string $metaKeywords
     */
    function setMetaKeywords($metaKeywords);
}

