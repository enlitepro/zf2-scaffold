<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace Scaffold\Console;


use Scaffold\State;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class EntityCommand extends AbstractCommand
{

    protected function configure()
    {
        $this->setName('entity');
        $this->setDescription('Generate entity');
        $this->addArgument('module', InputArgument::REQUIRED, 'Module name');
        $this->addArgument('name', InputArgument::REQUIRED, 'Entity name');
    }

    protected function write(State $state, InputInterface $input, OutputInterface $output)
    {
        $writeState = new State($this->configWriter);
        $writeState->addModel($state->getEntityModel());

        parent::write($writeState, $input, $output);
    }


}