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

class AdminController extends ContainerAware
{
    public function indexAction()
    {
        $nodes = $this->container->get('lyra_content.node_manager')
            ->findAllNodes();

        return $this->container
            ->get('templating')
            ->renderResponse('LyraContentBundle:Admin:index.html.twig', array(
                'nodes' => $nodes,
                'types' => $this->container->getParameter('lyra_content.types')
            ));
    }
    
    public function deleteAction($id)
    {
        $manager =  $this->container->get('lyra_content.node_manager');

        $node = $manager->findNode($id);
        if (!$node) {
            throw new NotFoundHttpException(sprintf('Node with id "%s" does not exist', $id));
        }
        
        $children = $manager->findNodeDescendants($node);
        if ('POST' === $this->container->get('request')->getMethod()) {
            $manager->removeNode($node);
            return new RedirectResponse($this->container->get('router')->generate('admin_content'));
        }
        
        return $this->container->get('templating')
            ->renderResponse('LyraContentBundle:Admin:delete.html.twig', array(
                'content' => $node,
                'children' => $children
            ));
    }
    
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
                return new RedirectResponse($this->container->get('router')->generate('admin_content'));
            }
        }
        
        return $this->container->get('templating')
            ->renderResponse('LyraContentBundle:Admin:move.html.twig', array(
                'form' => $form->createView(),
                'content' => $node
            ));
    }
    
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
                break;
            case 'unpublish':
                $manager->unpublishNode($node);
                break;
        }
        
        return new RedirectResponse($this->container->get('router')->generate('admin_content'));
    }
}
