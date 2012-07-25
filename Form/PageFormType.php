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

class PageFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('node', 'lyra_content_node_form');
        $builder->add('content', 'textarea');
        $builder->add('meta_title', 'text', array('required' => false));
        $builder->add('meta_description', 'textarea', array('required' => false));
        $builder->add('meta_keywords', 'textarea', array('required' => false));
    }

    public function getName()
    {
        return 'lyra_content_page';
    }
}
