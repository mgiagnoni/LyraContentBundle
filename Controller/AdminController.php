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
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Controller managing actions to administer content from frontend.
 */
class AdminController extends ContainerAware
{
    public function showAction($id)
    {
        $manager =  $this->container->get('lyra_content.node_manager');

        if (!$node = $manager->findNode($id)) {
            throw new NotFoundHttpException(sprintf('Node with id "%s" does not exist', $id));
        }

        $path = $manager->findNodeAscendants($node);

        $types = $this->container->getParameter('lyra_content.types');
        $contentBundle = $types[$node->getItemType()]['bundle'];

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

    public function moveAction($id, $dir)
    {
        $manager =  $this->container->get('lyra_content.node_manager');

        $node = $manager->findNode($id);
        if (!$node) {
            throw new NotFoundHttpException(sprintf('Node with id "%s" does not exist', $id));
        }

        if ('up' == $dir) {
            $manager->moveNodeUp($node);
        } else {
            $manager->moveNodeDown($node);
        }

        return $this->getRedirectToContentResponse($node->getParent());
    }

    public function deleteAction($id)
    {
        $manager =  $this->container->get('lyra_content.node_manager');

        $node = $manager->findNode($id);
        if (!$node) {
            throw new NotFoundHttpException(sprintf('Node with id "%s" does not exist', $id));
        }

        if ($node->isRoot()) {
            throw new HttpException(403, 'Root node cannot be deleted.');
        }

        $parent = $node->getParent();
        $manager->removeNode($node);

        return $this->getRedirectToContentResponse($parent);
    }

    public function descendantsAction($node)
    {
        $nodes = $this->container->get('lyra_content.node_manager')
            ->findNodeDescendantsFilteredByDepth($node);

        return $this->container->get('templating')
            ->renderResponse('LyraContentBundle:Admin:admin_descendants.html.twig', array(
                'nodes' => $nodes
            ));
    }

    protected function getRedirectToContentResponse($node)
    {
        return new RedirectResponse(
            $this->container->get('router')
                ->generate('lyra_content_manage', array('id' => $node->getId()))
        );
    }

}
