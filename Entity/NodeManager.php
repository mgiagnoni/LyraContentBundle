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

/**
 * Manager of node entities
 */
class NodeManager extends AbstractNodeManager
{
    /**
     * @var Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     * @var Doctrine\ORM\EntityRepository;
     */
    protected $repository;

    /**
     * @var string entity class name
     */
    protected $class;

    /**
     * @var array content types informations
     */
    protected $types;

    public function __construct(EntityManager $em, $class, $types)
    {
        $this->em = $em;
        $this->repository = $em->getRepository($class);
        $this->class = $class;
        $this->types = $types;
    }

    /**
     * {@inheritdoc}
     */
    public function findNode($nodeId)
    {
        return $this->repository->find($nodeId);
    }

    /**
     * {@inheritdoc}
     */
    public function findNodeBy(array $criteria)
    {
        return $this->repository->findOneBy($criteria);
    }

    /**
     * {@inheritdoc}
     */
    public function findAllNodes()
    {
        return $this->getNodeTreeQueryBuilder()
            ->getQuery()
            ->getResult();
    }

    /**
     * Returns the root node.
     *
     * @return NodeInterface
     */
    public function findRootNode()
    {
        return $this->repository->findOneBy(array('lvl' => 0));
    }

    /**
     * Returns the Query Builder to select all nodes ordered as tree.
     *
     * @return Doctrine\ORM\QueryBuilder
     */
    public function getNodeTreeQueryBuilder()
    {
        $qb = $this->repository
            ->createQueryBuilder('c')
            ->select('c, p')
            ->leftJoin('c.parent', 'p')
            ->orderBy('c.lft');

        return $qb;
    }

    /**
     * {@inheritdoc}
     */
    public function findNodeAscendants(NodeInterface $node)
    {
        return $this->getNodeAscendantsQueryBuilder($node)
            ->getQuery()->getResult();
    }

    /**
     * Returns ascendants of a given node filtered by published status.
     *
     * @param NodeInterface $node
     * @param Boolean $published
     * @return array
     */
    public function findNodeAscendantsFilteredByPublished(NodeInterface $node, $published)
    {
        $qb = $this->getNodeAscendantsQueryBuilder($node);

        $qb->andWhere('c.published = :pub')
            ->setParameter('pub', $published);

        return $qb->getQuery()->getResult();
    }

    /**
     * Returns the Query Builder to select node ascendants.
     *
     * @param NodeInterface $node
     * @return Doctrine\ORM\QueryBuilder
     */
    public function getNodeAscendantsQueryBuilder(NodeInterface $node)
    {
        $qb = $this->getNodeTreeQueryBuilder();
        $qb->where('c.lft <= :lft AND c.rgt >= :rgt')
            ->setParameter('lft', $node->getLeft())
            ->setParameter('rgt', $node->getRight());

        return $qb;
    }

    /**
     * {@inheritdoc}
     */
    public function findNodeDescendants(NodeInterface $node)
    {
        return $this->getNodeDescendantsQueryBuilder($node)
            ->getQuery()->getResult();
    }

    /**
     * Returns all published descendants of a given node up to a max depth.
     *
     * @param NodeInterface $node
     * @param integer $depth max depth relative to $node's depth
     * @return array
     */
    public function findNodePublishedDescendantsFilteredByDepth(NodeInterface $node, $depth = 1)
    {
        $qb = $this->getNodeDescendantsQueryBuilder($node);
        $qb->andWhere('c.published = true')
            ->andWhere('c.lvl - ' . $node->getLevel() . ' <= :d')
            ->setParameter('d', $depth);

        return $qb->getQuery()->getResult();
    }

    /**
     * Returns the Query Builder to select node descendants.
     *
     * @param NodeInterface $node
     * @return Doctrine\ORM\QueryBuilder
     */
    public function getNodeDescendantsQueryBuilder(NodeInterface $node)
    {
        $qb = $this->getNodeTreeQueryBuilder();
        $qb->where('c.lft > :lft AND c.rgt < :rgt')
            ->setParameter('lft', $node->getLeft())
            ->setParameter('rgt', $node->getRight());

        return $qb;
    }

    /**
     * {@inheritdoc}
     */
    public function findNodeNotDescendants(NodeInterface $node)
    {
        return $this->getNodeNotDescendantsQueryBuilder($node)
            ->getQuery()->getResult();
    }

    /**
     * Returns the Query Builder to select all nodes that are *not*
     * descendants of a given node.
     *
     * @param NodeInterface $node
     * @return Doctrine\ORM\QueryBuilder
     */
    public function getNodeNotDescendantsQueryBuilder(NodeInterface $node)
    {
        $qb = $this->getNodeTreeQueryBuilder();
        $qb->where('c.lft < :lft OR c.rgt > :rgt')
            ->setParameter('lft', $node->getLeft())
            ->setParameter('rgt', $node->getRight());

        return $qb;
    }

    /**
     * {@inheritdoc}
     */
    public function findPathNodes($path)
    {
        if (!$node = $this->findNodeByPath($path)) {
            return false;
        }

        return $this->findNodeAscendants($node);
    }

    /**
     * {@inheritdoc}
     */
    public function findPublishedPathNodes($path)
    {
        if (!$node = $this->findPublishedNodeByPath($path)) {
            return false;
        }

        return $this->findNodeAscendantsFilteredByPublished($node, true);
    }

    /**
     * {@inheritdoc}
     */
    public function findNodeContent(NodeInterface $node)
    {
        $model = $this->types[$node->getItemType()]['model'];

        return $this->em->getRepository($model)
            ->findOneBy(array('node' => $node->getId()));
    }

    /**
     * {@inheritdoc}
     */
    public function moveNodeUp(NodeInterface $node, $step = 1)
    {
        $this->repository->moveUp($node, $step);
    }

    /**
     * {@inheritdoc}
     */
    public function moveNodeDown(NodeInterface $node, $step = 1)
    {
        $this->repository->moveDown($node, $step);
    }

    /**
     * {@inheritdoc}
     */
    public function saveNode(NodeInterface $node)
    {
        $this->em->persist($node);
        $this->em->flush();

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function removeNode(NodeInterface $node)
    {
        $this->em->remove($node);
        $this->em->flush();
    }

    public function validateUniquePath(NodeInterface $node)
    {
        $existing = $this->findNodeBy(array('path' => $node->getPath()));

        if (null !== $existing && $existing->getId() !== $node->getId()) {

            return false;
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function getClass()
    {
        return $this->class;
    }
}
