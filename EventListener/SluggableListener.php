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

use Gedmo\Sluggable\SluggableListener as BaseSluggableListener;
use Doctrine\Common\EventArgs;

class SluggableListener extends BaseSluggableListener
{
    public function onFlush(EventArgs $args)
    {
        parent::onFlush($args);

        $ea = $this->getEventAdapter($args);
        $om = $ea->getObjectManager();
        $uow = $om->getUnitOfWork();

        foreach ($ea->getScheduledObjectInsertions($uow) as $object) {
            $meta = $om->getClassMetadata(get_class($object));
            if ($this->getConfiguration($om, $meta->name) && null !== $object->getParent()) {
                 $object->setPath($object->getPath() . $object->getSlug());
                 $uow->recomputeSingleEntityChangeSet($meta, $object);
            }
        }
    }
}

