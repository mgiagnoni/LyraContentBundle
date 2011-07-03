<?php

/*
 * This file is part of the LyraContentBundle package.
 * 
 * Copyright 2011 Massimo Giagnoni <gimassimo@gmail.com>
 *
 * This source file is subject to the MIT license. Full copyright and license
 * information are in the LICENSE file distributed with this source code.
 */

namespace Lyra\ContentBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Lyra\ContentBundle\Entity\Node;
use Lyra\ContentBundle\Entity\Page;

class LoadContentData implements FixtureInterface
{
    public function load($manager)
    {
        $c0 = new Node();
        $c0->setTitle('Home');
        $c0->setType('page');
        $c0->setPublished(true);
        $p0 = new Page();
        $p0->setContent('Home page');
        $p0->setNode($c0);

        $c1 = new Node();
        $c1->setTitle('Page');
        $c1->setPublished(true);
        $c1->setType('page');
        $c1->setParent($c0);
        $p1 = new Page();
        $p1->setContent('Page content');
        $p1->setNode($c1);

        $c2 = new Node();
        $c2->setTitle('Subpage1');
        $c2->setType('page');
        $c2->setPublished(true);
        $c2->setParent($c1);
        $p2 = new Page();
        $p2->setContent('Subpage1 content');
        $p2->setNode($c2);

        $manager->persist($p0);
        $manager->persist($p1);
        $manager->persist($p2);

        $manager->flush();
    }
}
