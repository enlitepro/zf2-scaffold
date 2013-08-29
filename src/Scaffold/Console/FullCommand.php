<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace Scaffold\Console;

use Symfony\Component\Console\Input\InputArgument;

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
