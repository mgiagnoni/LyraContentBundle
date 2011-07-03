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

class PageController extends ContainerAware
{
    public function newAction()
    {
        $page = $this->container->get('lyra_content.page_manager')
            ->createPage();
        
        $form = $this->container->get('lyra_content.page.form');
        $form->setData($page);

        $request = $this->container->get('request');
        if ('POST' == $request->getMethod()) {
            $form->bindRequest($request);
            if ($form->isValid() && $this->container->get('lyra_content.page_manager')->savePage($page)) {
                return new RedirectResponse($this->container->get('router')->generate('admin_content'));
            }
        } 

        return $this->container->get('templating')
            ->renderResponse('LyraContentBundle:Admin:new.html.twig', array(
                'form' => $form->createView(),
            ));
    }
    
    public function editAction($id)
    {
        $page = $this->container->get('lyra_content.page_manager')
            ->findPageByNodeId($id);

        $form = $this->container->get('lyra_content.page.form');
        $form->setData($page);

        $request = $this->container->get('request');
        if ('POST' === $request->getMethod()) {
            $form->bindRequest($request);
            if ($form->isValid() && $this->container->get('lyra_content.page_manager')->savePage($page)) {
                return new RedirectResponse($this->container->get('router')->generate('admin_content'));
            }
        }

        return $this->container->get('templating')
            ->renderResponse('LyraContentBundle:Admin:edit.html.twig', array(
                'form' => $form->createView(),
                'node' => $page->getNode(),
            ));
    }
}
