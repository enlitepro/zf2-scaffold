<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace Scaffold\Console;

use Scaffold\Builder\Container\ExceptionContainer;
use Scaffold\Builder\Container\FullContainer;
use Scaffold\Builder\Container\OptionsContainer;
use Scaffold\Builder\Entity\EntityBuilder;
use Scaffold\Builder\SimpleContainer;
use Scaffold\Config;
use Scaffold\State;
use Scaffold\Writer\ConfigWriter;
use Scaffold\Writer\ModelWriter;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class OptionsCommand extends AbstractCommand
{
    protected function configure()
    {
        $this->setName('options');
        $this->setDescription('Generate options, options DI trait, options factory and write to service.config.php');
        $this->addArgument('module', InputArgument::REQUIRED, 'Module name');
        $this->addArgument('name', InputArgument::REQUIRED, 'Options name (will be append Options postfix)');
        $this->addOption(
            'no-options',
            null,
            InputOption::VALUE_NONE,
            'Disable options generation'
        );
        $this->addOption(
            'no-trait',
            null,
            InputOption::VALUE_NONE,
            'Disable trait generation'
        );
        $this->addOption(
            'no-factory',
            null,
            InputOption::VALUE_NONE,
            'Disable factory generation'
        );

        $this->addOption(
            'only-options',
            null,
            InputOption::VALUE_NONE,
            'Generate only options'
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
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $config = new Config();
        $config->setBasePath(getcwd());
        $config->setFromArray($input->getArguments());

        $moduleConfig = new ConfigWriter($config);

        $state = new State($moduleConfig);

        $builder = new OptionsContainer($config);
        $builder->addBuilder(new ExceptionContainer($config));

        $builder->prepare($state);
        $builder->build($state);

        $writeState = new State($moduleConfig);

        $models = array(
            'options' => $state->getModel('options'),
            'factory' => $state->getModel('options-factory'),
            'trait' => $state->getModel('options-trait'),
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
