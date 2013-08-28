<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace Scaffold\Console;


use Scaffold\State;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FormCommand extends AbstractCommand
{

    protected function configure()
    {
        $this->setName('form');
        $this->setDescription('Generate form factory and write to service.config.php');
        $this->addArgument('module', InputArgument::REQUIRED, 'Module name');
        $this->addArgument('name', InputArgument::REQUIRED, 'Form name');
    }

    protected function write(State $state, InputInterface $input, OutputInterface $output)
    {
        $writeState = new State($this->configWriter);
        $writeState->addModel($state->getFormFactoryModel());

        parent::write($writeState, $input, $output);
    }


}