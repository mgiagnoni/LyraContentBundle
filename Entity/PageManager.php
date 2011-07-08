<?php

/*
 * This file is part of the LyraContentBundle package.
 * 
 * Copyright 2011 Massimo Giagnoni <gimassimo@gmail.com>
 *
 * This source file is subject to the MIT license. Full copyright and license
 * information are in the LICENSE file distributed with this source code.
 */

namespace Lyra\ContentBundle\Entity;

use Doctrine\ORM\EntityManager;
use Lyra\ContentBundle\Model\PageInterface;
use Lyra\ContentBundle\Model\PageManager as AbstractPageManager;

class PageManager extends AbstractPageManager
{
    protected $em;
    protected $repository;
    protected $class;

    public function __construct(EntityManager $em, $class)
    {
        $this->em = $em;
        $this->repository = $em->getRepository($class);
        $this->class = $class;
    }

    public function findPageByNodeId($nodeId)
    {
        $qb = $this->repository
            ->createQueryBuilder('p')
            ->select('p, n')
            ->innerJoin('p.node', 'n')
            ->where('n.id = :nid')
            ->setParameter('nid', $nodeId);

        return $qb->getQuery()->getSingleResult();
    }

    public function savePage(PageInterface $page)
    {
        $this->updateLinkedNode($page->getNode());
        $this->em->persist($page);
        $this->em->flush();

        return true;
    }

    public function getClass()
    {
        return $this->class;
    }
}
