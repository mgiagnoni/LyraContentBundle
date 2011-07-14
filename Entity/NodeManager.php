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
use Lyra\ContentBundle\Model\NodeManager as AbstractNodeManager;
use Lyra\ContentBundle\Model\NodeInterface;

class NodeManager extends AbstractNodeManager
{
    protected $em;
    protected $repository;
    protected $class;
    protected $types;

    public function __construct(EntityManager $em, $class, $types)
    {
        $this->em = $em;
        $this->repository = $em->getRepository($class);
        $this->class = $class;
        $this->types = $types;
    }

    public function findNode($nodeId)
    {
        return $this->repository->find($nodeId);
    }

    public function findNodeBy(array $criteria)
    {
        return $this->repository->findOneBy($criteria);
    }

    public function findAllNodes()
    {
        return $this->getNodeTreeQueryBuilder()
            ->getQuery()
            ->getResult();
    }

    public function findRootNode()
    {
        return $this->repository->findOneBy(array('lvl' => 0));
    }

    public function getNodeTreeQueryBuilder()
    {
        $qb = $this->repository
            ->createQueryBuilder('c')
            ->select('c, p')
            ->leftJoin('c.parent', 'p')
            ->orderBy('c.lft');

        return $qb;
    }
    
    public function findNodeAscendants(NodeInterface $node)
    {
        return $this->getNodeAscendantsQueryBuilder($node)
            ->getQuery()->getResult();
    }

    public function findNodeAscendantsFilteredByPublished(NodeInterface $node, $published)
    {
        $qb = $this->getNodeAscendantsQueryBuilder($node);

        $qb->andWhere('c.published = :pub')
            ->setParameter('pub', $published);

        return $qb->getQuery()->getResult();
    }

    public function getNodeAscendantsQueryBuilder(NodeInterface $node)
    {
        $qb = $this->getNodeTreeQueryBuilder();
        $qb->where('c.lft <= :lft AND c.rgt >= :rgt')
            ->setParameter('lft', $node->getLeft())
            ->setParameter('rgt', $node->getRight());
    
        return $qb;
    }

    public function findNodeDescendants(NodeInterface $node)
    {
        return $this->getNodeDescendantsQueryBuilder($node)
            ->getQuery()->getResult();
    }

    public function getNodeDescendantsQueryBuilder(NodeInterface $node)
    {
        $qb = $this->getNodeTreeQueryBuilder();
        $qb->where('c.lft > :lft AND c.rgt < :rgt')
            ->setParameter('lft', $node->getLeft())
            ->setParameter('rgt', $node->getRight());
    
        return $qb;
    }

    public function findNodeNotDescendants(NodeInterface $node)
    {
        return $this->getNodeNotDescendantsQueryBuilder($node)
            ->getQuery()->getResult();
    }

    public function getNodeNotDescendantsQueryBuilder(NodeInterface $node)
    {
        $qb = $this->getNodeTreeQueryBuilder();
        $qb->where('c.lft < :lft OR c.rgt > :rgt')
            ->setParameter('lft', $node->getLeft())
            ->setParameter('rgt', $node->getRight());

        return $qb;
    }

    public function findPathNodes($path)
    {
        if (!$node = $this->findNodeByPath($path)) {
            return false;
        }

        return $this->findNodeAscendants($node);
    }

    public function findPublishedPathNodes($path)
    {
        if (!$node = $this->findPublishedNodeByPath($path)) {
            return false;
        }

        return $this->findNodeAscendantsFilteredByPublished($node, true);
    }

    public function findNodeContent(NodeInterface $node)
    {
        $model = $this->types[$node->getType()]['model'];

        return $this->em->getRepository($model)
            ->findOneBy(array('node' => $node->getId()));
    }

    public function moveNodeUp(NodeInterface $node, $step = 1)
    {
        $this->repository->moveUp($node, $step);
    }

    public function moveNodeDown(NodeInterface $node, $step = 1)
    {
        $this->repository->moveDown($node, $step);
    }
    
    public function saveNode(NodeInterface $node)
    {
        $this->em->persist($node);
        $this->em->flush();

        return true;
    }
    
    public function removeNode(NodeInterface $node)
    {
        $this->em->remove($node);
        $this->em->flush();
    }

    public function getClass()
    {
        return $this->class;
    }
}
