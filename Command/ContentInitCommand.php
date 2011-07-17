<?php

/*
 * This file is part of the LyraContentBundle package.
 *
 * Copyright 2011 Massimo Giagnoni <gimassimo@gmail.com>
 *
 * This source file is subject to the MIT license. Full copyright and license
 * information are in the LICENSE file distributed with this source code.
 */

namespace Lyra\ContentBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command to initialize content table by creating a root node.
 */
class ContentInitCommand extends ContainerAwareCommand
{
    /**
     * @see Symfony\Component\Console\Command
     */
    protected function configure()
    {
        $this
            ->setName('lyra:content:init')
            ->setDescription('Initializes content table')
            ->setHelp(<<<EOT
The <info>lyra:content:init</info> command initializes content table by creating a root node (home page).

  <info>php app/console lyra:content:init</info>
EOT
            );
    }

    /**
     * @see Symfony\Component\Console\Command
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $nodeManager = $this->getContainer()->get('lyra_content.node_manager');

        if (null !== $nodeManager->findRootNode()) {
            throw new \RuntimeException('A root node already exists!');
        }

        $node = $nodeManager->createNode();
        $node->setTitle('Home');
        $node->setPublished(true);
        $node->setType('page');

        $pageManager = $this->getContainer()->get('lyra_content.page_manager');
        $page = $pageManager->createPage();
        $page->setContent('Home page');
        $page->setNode($node);
        $pageManager->savePage($page);

        $output->writeln('Root node has been successfully created.');
    }
}
