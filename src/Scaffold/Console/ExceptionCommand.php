<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace Scaffold\Console;


use Scaffold\Builder\EntityContainer;
use Scaffold\Config;
use Scaffold\State;
use Scaffold\Writer\ConfigWriter;
use Scaffold\Writer\ModelWriter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ExceptionCommand extends Command
{
    protected function configure()
    {
        $this->setName('exception');
        $this->setDescription('Create exceptions');
        $this->addArgument('module', InputArgument::REQUIRED, 'Module name');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $config = new Config();
        $config->setBasePath(getcwd());
        $config->setFromArray($input->getArguments());

        $moduleConfig = new ConfigWriter($config);

        $state = new State($moduleConfig);

        $builder = new EntityContainer($config);
        $builder->prepare($state);
        $builder->build($state);

        $writeState = new State($moduleConfig);
        $writeState->addModel($state->getRuntimeException());
        $writeState->addModel($state->getNotFoundException());

        $writer = new ModelWriter($config);
        $writer->write($writeState, $output);
    }
}