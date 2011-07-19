<?php

/*
 * This file is part of the LyraContentBundle package.
 *
 * Copyright 2011 Massimo Giagnoni <gimassimo@gmail.com>
 *
 * This source file is subject to the MIT license. Full copyright and license
 * information are in the LICENSE file distributed with this source code.
 */

namespace Lyra\ContentBundle\EventListener;

use Gedmo\Tree\TreeListener as BaseTreeListener;
use Doctrine\Common\EventArgs;
use Lyra\ContentBundle\Model\NodeInterface;

class TreeListener extends BaseTreeListener
{
    public function onFlush(EventArgs $args)
    {
        parent::onFlush($args);

        $ea = $this->getEventAdapter($args);
        $om = $ea->getObjectManager();
        $uow = $om->getUnitOfWork();

        foreach ($ea->getScheduledObjectInsertions($uow) as $object) {
            $meta = $om->getClassMetadata(get_class($object));
            if ($object instanceof NodeInterface && null !== $parent = $object->getParent()) {
                $object->setPath(($parent->getPath() ? $parent->getPath() . '/' : '') . $object->getPath());
                $uow->recomputeSingleEntityChangeSet($meta, $object);
            }
        }
    }
}
