<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace Scaffold\Console;


use Scaffold\Builder\Container\FullContainer;
use Scaffold\Config;
use Scaffold\State;
use Scaffold\Writer\ConfigWriter;
use Scaffold\Writer\ModelWriter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ServiceCommand extends AbstractCommand
{
    protected function configure()
    {
        $this->setName('service');
        $this->setDescription('Generate service, service DI trait, service factory, service test and write to service.config.php');
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

        $this->addOption(
            'only-service',
            null,
            InputOption::VALUE_NONE,
            'Generate only service'
        );
        $this->addOption(
            'only-trait',
            null,
            InputOption::VALUE_NONE,
            'Generate only trait'
        );
        $this->addOption(
            'only-factory',
            null,
            InputOption::VALUE_NONE,
            'Generate only factory'
        );
        $this->addOption(
            'only-test',
            null,
            InputOption::VALUE_NONE,
            'Generate only test'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $config = new Config();
        $config->setBasePath(getcwd());
        $config->setFromArray($input->getArguments());

        $moduleConfig = new ConfigWriter($config);

        $state = new State($moduleConfig);

        $builder = new FullContainer($config);
        $builder->prepare($state);
        $builder->build($state);

        $writeState = new State($moduleConfig);

        $models = array(
            'service' => $state->getServiceModel(),
            'factory' => $state->getModel('service-factory'),
            'trait' => $state->getModel('service-trait'),
            'test' => $state->getModel('service-test'),
        );

        foreach (array_keys($models) as $key) {
            if ($input->getOption('no-' . $key)) {
                $models[$key] = false;
            }

            if ($input->getOption('only-' . $key)) {
                foreach (array_keys($models) as $index) {
                    if ($key != $index) {
                        $models[$index] = false;
                    }
                }
            }
        }

        foreach ($models as $model) {
            if ($model) {
                $writeState->addModel($model);
            }
        }

        $writer = new ModelWriter($config);
        $writer->write($writeState, $output);

        $moduleConfig->save($output);
    }
}