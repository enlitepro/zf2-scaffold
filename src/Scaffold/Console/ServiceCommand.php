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

class ServiceCommand extends Command
{
    protected function configure()
    {
        $this->setName('service');
        $this->setDescription('Create service');
        $this->addArgument('module', InputArgument::REQUIRED, 'Module name');
        $this->addArgument('name', InputArgument::REQUIRED, 'Service name');
        $this->addOption(
            'no-service',
            null,
            InputOption::VALUE_NONE,
            'Disable service generation'
        );
        $this->addOption(
            'no-trait',
            null,
            InputOption::VALUE_NONE,
            'Disable service trait generation'
        );
        $this->addOption(
            'no-factory',
            null,
            InputOption::VALUE_NONE,
            'Disable service factory generation'
        );
        $this->addOption(
            'no-test',
            null,
            InputOption::VALUE_NONE,
            'Disable service test generation'
        );
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

        if (!$input->getOption('no-service')) {
            $writeState->addModel($state->getServiceModel());
        }

        if (!$input->getOption('no-factory')) {
            $writeState->addModel($state->getModel('service-factory'));
        }

        if (!$input->getOption('no-trait')) {
            $writeState->addModel($state->getModel('service-trait'));
        }

        if (!$input->getOption('no-test')) {
            $writeState->addModel($state->getModel('service-test'));
        }

        $writer = new ModelWriter($config);
        $writer->write($writeState, $output);

        $moduleConfig->save($output);
    }
}