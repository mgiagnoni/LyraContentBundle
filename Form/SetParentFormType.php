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
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Event\DataEvent;
use Doctrine\ORM\EntityRepository;

class SetParentFormType extends AbstractType
{
    protected $manager;

    public function __construct($manager)
    {
        $this->manager = $manager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('parent', 'entity', array(
            'class' => $this->manager->getClass(),
            'query_builder' => $this->manager->getNodeTreeQueryBuilder()
        ));

        $factory = $builder->getFormFactory();
        $manager = $this->manager;

        $buildParent = function ($form, $node) use ($factory, $manager, $options)
        {
            if ($node->isRoot()) {
                $form->remove('parent');
                return;
            }

            $form->add($factory->createNamed('parent', 'entity', null, array(
               'class' => $manager->getClass(),
               'query_builder' => $manager->getNodeNotDescendantsQueryBuilder($node),
               'label' => 'Parent',
           )));
        };

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (DataEvent $event) use ($buildParent)
            {
               $form = $event->getForm();
               $data = $event->getData();

               if ($data instanceof \Lyra\ContentBundle\Entity\Node) {
                   $buildParent($form, $data);
               }
            }
        );

        $builder->addEventListener(FormEvents::POST_BIND, function (DataEvent $event) use ($manager)
            {
                $data = $event->getData();

                if ($data instanceof \Lyra\ContentBundle\Entity\Node) {
                   $manager->updateNode($data);
                }
            }
        );
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
          'data_class' => $this->manager->getClass()
       ));
    }

    public function getName()
    {
        return 'lyra_content_set_parent';
    }
}
