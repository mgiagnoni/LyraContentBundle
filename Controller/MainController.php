<?php

/*
 * This file is part of the LyraContentBundle package.
 * 
 * Copyright 2011 Massimo Giagnoni <gimassimo@gmail.com>
 *
 * This source file is subject to the MIT license. Full copyright and license
 * information are in the LICENSE file distributed with this source code.
 */

namespace Lyra\ContentBundle\Controller;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Controller to show content in frontend.
 */
class MainController extends ContainerAware
{
    /**
     * Displays a content item.
     *
     * @param string $path node path
     */
    public function showAction($path)
    {
        $path = $this->container->get('lyra_content.node_manager')
            ->findPublishedPathNodes(trim($path, '/'));
        
        if (false === $path) {
            throw new NotFoundHttpException('Page not found!');
        }

        $node = end($path);
        $types = $this->container->getParameter('lyra_content.types');
        $contentBundle = $types[$node->getType()]['bundle'];

        $item = $this->container->get('lyra_content.node_manager')
            ->findNodeContent($node);

        return $this->container
            ->get('templating')
            ->renderResponse($contentBundle . ':Main:show.html.twig', array(
                'node' => $node,
                'path' => $path,
                'item' => $item
            ));
    }
}
