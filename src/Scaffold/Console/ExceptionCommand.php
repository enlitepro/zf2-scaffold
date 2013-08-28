<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace Scaffold\Console;


use Scaffold\Builder\BuilderInterface;
use Scaffold\Builder\Container\ExceptionContainer;
use Scaffold\Builder\Container\FullContainer;
use Scaffold\State;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ExceptionCommand extends AbstractCommand
{
    protected function configure()
    {
        $this->setName('exception');
        $this->setDescription('Generate exceptions (RuntimeException, NotFoundException and other)');
        $this->addArgument('module', InputArgument::REQUIRED, 'Module name');
    }

    protected function getBuilder()
    {
        return new ExceptionContainer($this->config);
    }

}