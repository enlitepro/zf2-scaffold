<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace Scaffold\Console;

use Scaffold\Builder\Container\ExceptionContainer;
use Symfony\Component\Console\Input\InputArgument;

class ExceptionCommand extends AbstractCommand
{
    protected function configure()
    {
        parent::configure();

        $this->setName('exception');
        $this->setDescription('Generate exceptions (RuntimeException, NotFoundException and other)');
        $this->addArgument('module', InputArgument::REQUIRED, 'Module name');
    }

    protected function getBuilder()
    {
        return new ExceptionContainer($this->config);
    }

}
