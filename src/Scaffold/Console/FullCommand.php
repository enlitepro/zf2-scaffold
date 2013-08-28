<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace Scaffold\Console;

use Scaffold\Builder;
use Scaffold\Config;
use Scaffold\State;
use Scaffold\Writer\ConfigWriter;
use Scaffold\Writer\ModelWriter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FullCommand extends AbstractCommand
{

    protected function configure()
    {
        $this->setName('full');
        $this->setDescription('Generate all available (without module skeleton)');
        $this->addArgument('module', InputArgument::REQUIRED, 'Module name');
        $this->addArgument('name', InputArgument::REQUIRED, 'Entity name');
    }

}