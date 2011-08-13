<?php

/*
 * This file is part of the LyraContentBundle package.
 *
 * Copyright 2011 Massimo Giagnoni <gimassimo@gmail.com>
 *
 * This source file is subject to the MIT license. Full copyright and license
 * information are in the LICENSE file distributed with this source code.
 */

namespace Lyra\ContentBundle\Validator;

use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraint;
use Lyra\ContentBundle\Model\NodeManagerInterface;

class PathValidator extends ConstraintValidator
{
    /**
     * @var NodeManagerInterface
     */
    protected $nodeManager;

    public function __construct(NodeManagerInterface $nodeManager)
    {
        $this->nodeManager = $nodeManager;
    }

    public function setNodeManager(NodeManagerInterface $nodeManager)
    {
        $this->nodeManager = $nodeManager;
    }

    public function getNodeManager()
    {
        return $this->nodeManager;
    }

    public function isValid($object, Constraint $constraint)
    {
        if (!$this->getNodeManager()->validateUniquePath($object)) {
            $this->setMessage($constraint->message);

            return false;
        }

        return true;
    }
}
