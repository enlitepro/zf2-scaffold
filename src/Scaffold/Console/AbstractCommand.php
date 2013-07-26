<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace Scaffold\Console;


use Scaffold\Builder\BuilderInterface;
use Scaffold\Builder\Container\FullContainer;
use Scaffold\Config;
use Scaffold\State;
use Scaffold\Writer\ConfigWriter;
use Scaffold\Writer\ModelWriter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class AbstractCommand extends Command
{

    /**
     * @var Config
     */
    protected $config;

    /**
     * @var ConfigWriter
     */
    protected $configWriter;

    /**
     * @var State
     */
    protected $state;

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->prepareConfig($input);

        $state = new State($this->configWriter);

        $builder = $this->getBuilder();
        $builder->prepare($state);
        $builder->build($state);

        $this->write($state, $input, $output);
        $this->configWriter->save($output);
    }

    /**
     * Prepare config
     *
     * @param InputInterface $input
     */
    protected function prepareConfig(InputInterface $input)
    {
        $this->config = new Config();
        $this->config->setBasePath(getcwd());
        $this->config->setFromArray($input->getArguments());

        $this->configWriter = new ConfigWriter($this->config);
    }

    /**
     * @return BuilderInterface
     */
    protected function getBuilder()
    {
        return new FullContainer($this->config);
    }

    /**
     * @param State $state
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function write(State $state, InputInterface $input, OutputInterface $output)
    {
        $writer = new ModelWriter($this->config);
        $writer->write($state, $output);
    }

}