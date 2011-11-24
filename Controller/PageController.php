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
                return $this->getRedirectToContentResponse($page);
            }
        }

        return $this->getRenderFormResponse($form, 'new');
    }

    public function editAction($id)
    {
        $page = $this->container->get('lyra_content.page_manager')
            ->findPage($id);

        $form = $this->container->get('lyra_content.page.form');
        $form->setData($page);

        $request = $this->container->get('request');
        if ('POST' === $request->getMethod()) {
            $form->bindRequest($request);

            if ($form->isValid() && $this->container->get('lyra_content.page_manager')->savePage($page)) {
               return $this->getRedirectToContentResponse($page);
            }
        }

        return $this->getRenderFormResponse($form, 'edit');
    }

    /**
     * Returns the response to render the form.
     *
     * @param \Symfony\Component\Form\Form $form
     * @param string $action 'edit' or 'new'
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function getRenderFormResponse($form, $action)
    {
        return $this->container->get('templating')
            ->renderResponse(sprintf('LyraContentBundle:Page:%s.html.twig', $action), array(
                'form' => $form->createView(),
                'node' => $form->getData()->getNode(),
            ));
    }

    protected function getRedirectToContentResponse($page)
    {
        return new RedirectResponse(
            $this->container->get('router')
                ->generate('lyra_content_manage', array('id' => $page->getNode()->getId()))
        );
    }
}
