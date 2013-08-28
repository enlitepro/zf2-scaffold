<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace Scaffold\Console;


use Scaffold\State;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class RepositoryCommand extends AbstractCommand
{
    protected function configure()
    {
        $this->setName('repository');
        $this->setDescription('Generate repository and repository DI trait');
        $this->addArgument('module', InputArgument::REQUIRED, 'Module name');
        $this->addArgument('name', InputArgument::REQUIRED, 'Repository name');
        $this->addOption('no-trait', null, InputOption::VALUE_NONE, 'Generate without trait');
        $this->addOption('no-repository', null, InputOption::VALUE_NONE, 'Generate without repository');
    }

    protected function write(State $state, InputInterface $input, OutputInterface $output)
    {
        $writeState = new State($this->configWriter);

        if (!$input->getOption('no-repository')) {
            $writeState->addModel($state->getRepositoryModel());
        }

        if (!$input->getOption('no-trait')) {
            $writeState->addModel($state->getModel('repository-trait'));
        }

        parent::write($writeState, $input, $output);
    }


}