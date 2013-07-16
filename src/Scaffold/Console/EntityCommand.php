<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace Scaffold\Console;

use Scaffold\Builder;
use Scaffold\Entity\Config;
use Scaffold\Entity\State;
use Scaffold\Writer\ConfigWriter;
use Scaffold\Writer\ModelWriter;
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

        $builder = new Builder\Container();
        $builder->addBuilder(new Builder\EntityBuilder($config));
        $builder->addBuilder(new Builder\RepositoryBuilder($config));
        $builder->addBuilder(new Builder\ServiceBuilder($config));
        $builder->addBuilder(new Builder\ExceptionBuilder($config));
        $builder->addBuilder(new Builder\ControllerBuilder($config));
        $builder->addBuilder(new Builder\FormFactoryBuilder($config));
        $builder->addBuilder(new Builder\ServiceTraitBuilder($config));
        $builder->addBuilder(new Builder\EntityTestBuilder($config));
        $builder->addBuilder(new Builder\ServiceTestBuilder($config));
        $builder->prepare($state);
        $builder->build($state);

        $writer = new ModelWriter($config);
        $writer->write($state, $output);

        $moduleConfig->save($output);
    }


}