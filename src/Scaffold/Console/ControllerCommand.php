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
use Symfony\Component\Console\Output\OutputInterface;

class ControllerCommand extends Command
{

    protected function configure()
    {
        $this->setName('controller');
        $this->setDescription('Create controller');
        $this->addArgument('module', InputArgument::REQUIRED, 'Module name');
        $this->addArgument('name', InputArgument::REQUIRED, 'Controller name');
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
        $writeState->addModel($state->getControllerModel());

        $writer = new ModelWriter($config);
        $writer->write($writeState, $output);

        $moduleConfig->save($output);
    }

}