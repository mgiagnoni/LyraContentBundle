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
 * Controller managing actions to administer nodes.
 */
class AdminController extends ContainerAware
{
    /**
     * Displays a list of nodes.
     */
    public function indexAction()
    {
        $nodes = $this->container->get('lyra_content.node_manager')
            ->findAllNodes();

        return $this->container
            ->get('templating')
            ->renderResponse('LyraContentBundle:Node:index.html.twig', array(
                'nodes' => $nodes,
                'types' => $this->container->getParameter('lyra_content.types')
            ));
    }

    /**
     * Deletes a node.
     *
     * @param mixed $id node id
     */
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

        $form = $this->container->get('form.factory')
            ->createBuilder('form')
            ->getForm();

        $children = $manager->findNodeDescendants($node);
        if ('POST' === $this->container->get('request')->getMethod()) {
            $manager->removeNode($node);
            $this->setFlash('lyra_content success', 'flash.delete.success');

            return new RedirectResponse($this->container->get('router')->generate('lyra_content_admin_list'));
        }

        return $this->container->get('templating')
            ->renderResponse('LyraContentBundle:Node:delete.html.twig', array(
                'content' => $node,
                'children' => $children,
                'form' => $form->createView()
            ));
    }

    /**
     * Moves a sub-tree (node + all its descendants) under a new parent.
     *
     * @param mixed $id node id
     */
    public function moveAction($id)
    {
        $manager =  $this->container->get('lyra_content.node_manager');

        $node = $manager->findNode($id);
        if (!$node) {
            throw new NotFoundHttpException(sprintf('Node with id "%s" does not exist', $id));
        }

        $form = $this->container->get('lyra_content.move_node.form');
        $form->setData($node);

        $request = $this->container->get('request');
        if ('POST' === $request->getMethod()) {
            $form->bindRequest($request);
            if ($form->isValid() && $manager->saveNode($node)) {
                return new RedirectResponse($this->container->get('router')->generate('lyra_content_admin_list'));
            }
        }

        return $this->container->get('templating')
            ->renderResponse('LyraContentBundle:Node:move.html.twig', array(
                'form' => $form->createView(),
                'content' => $node
            ));
    }

    /**
     * Moves up/down, publish/unpublish node.
     */
    public function objectAction()
    {
        $reqAction = $this->container->get('request')->get('action');
        $action = key($reqAction);
        $id = key($reqAction[$action]);

        $manager =  $this->container->get('lyra_content.node_manager');

        $node = $manager->findNode($id);
        if (!$node) {
            throw new NotFoundHttpException(sprintf('Node with id "%s" does not exist', $id));
        }

        switch ($action) {
            case 'moveup':
                $manager->moveNodeUp($node);
                break;
            case 'movedown':
                $manager->moveNodeDown($node);
                break;
            case 'publish':
                $manager->publishNode($node);
                $this->setFlash('lyra_content success', 'flash.publish.success');
                break;
            case 'unpublish':
                $manager->unpublishNode($node);
                $this->setFlash('lyra_content success', 'flash.unpublish.success');

                break;
        }

        return new RedirectResponse($this->container->get('router')->generate('lyra_content_admin_list'));
    }

    protected function setFlash($action, $value)
    {
        $this->container->get('session')->setFlash($action, $value);
    }
}
