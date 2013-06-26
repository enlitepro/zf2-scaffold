<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace Scaffold\Console;

use Scaffold\Builder\Container;
use Scaffold\Builder\ControllerBuilder;
use Scaffold\Builder\EntityBuilder;
use Scaffold\Builder\ExceptionBuilder;
use Scaffold\Builder\FormBuilder;
use Scaffold\Builder\RepositoryBuilder;
use Scaffold\Builder\ServiceBuilder;
use Scaffold\Entity\Config;
use Scaffold\Entity\State;
use Scaffold\ConfigWriter;
use Scaffold\ModelWriter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class EntityCommand extends Command
{

    protected function configure()
    {
        $this->setName('entity');
        $this->setDescription('Create entity, service and repository');
        $this->addArgument('module', InputArgument::REQUIRED, 'Module name');
        $this->addArgument('name', InputArgument::REQUIRED, 'Entity name');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $config = new Config();
        $config->setBasePath(getcwd());
        $config->setFromArray($input->getArguments());

        $moduleConfig = new ConfigWriter($config);

        $state = new State($moduleConfig);

        $builder = new Container();
        $builder->addBuilder(new EntityBuilder($config));
        $builder->addBuilder(new RepositoryBuilder($config));
        $builder->addBuilder(new ServiceBuilder($config));
        $builder->addBuilder(new ExceptionBuilder($config));
        $builder->addBuilder(new ControllerBuilder($config));
        $builder->addBuilder(new FormBuilder($config));
        $builder->prepare($state);
        $builder->build($state);

        $writer = new ModelWriter($config);
        $writer->write($state, $output);

        $moduleConfig->save($output);
    }


}