<?php

/*
 * This file is part of the LyraContentBundle package.
 *
 * Copyright 2011 Massimo Giagnoni <gimassimo@gmail.com>
 *
 * This source file is subject to the MIT license. Full copyright and license
 * information are in the LICENSE file distributed with this source code.
 */

namespace Lyra\ContentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Event\DataEvent;

class NodeFormType extends AbstractType
{
    protected $manager;

    public function __construct($manager)
    {
        $this->manager = $manager;
    }

    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('parent', 'entity', array(
            'class' => $options['data_class'],
            'query_builder' => $this->manager->getNodeTreeQueryBuilder()
        ));

        $builder->add('title');
        $builder->add('published', 'checkbox', array('required' => false));
        $builder->add('type', 'text', array('required' => false));
        $builder->add('link_title', 'text', array('required' => false));
        $builder->add('path', 'text', array('required' => false));

        $factory = $builder->getFormFactory();
        $manager = $this->manager;

        $buildParent = function ($form, $node) use ($factory, $manager, $options) {
            if ($node->isRoot()) {
                $form->remove('parent');
                return;
            }
            $form->add($factory->createNamed('entity', 'parent', null, array(
               'class' => $options['data_class'],
               'query_builder' => $manager->getNodeNotDescendantsQueryBuilder($node),
               'label' => 'Parent',
           )));
        };

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function
            (DataEvent $event) use ($buildParent) {
               $form = $event->getForm();
               $data = $event->getData();

               if ($data instanceof \Lyra\ContentBundle\Entity\Node) {
                   $buildParent($form, $data);
               }
            });
    }

    public function getName()
    {
        return 'lyra_content_node';
    }

}
