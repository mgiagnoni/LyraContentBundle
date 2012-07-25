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

use Symfony\Component\Form\FormBuilderInterface;

class NodeFormType extends SetParentFormType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder->add('title');
        $builder->add('published', 'checkbox', array('required' => false));
        $builder->add('link_title', 'text', array('required' => false));
        $builder->add('path', 'text', array('required' => false));
    }

    public function getName()
    {
        return 'lyra_content_node_form';
    }

}
