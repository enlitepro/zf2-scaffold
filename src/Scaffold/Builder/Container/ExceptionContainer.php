<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace Scaffold\Builder\Container;


use Scaffold\Builder\AbstractContainer;
use Scaffold\Builder\Exception\ExceptionBuilder;
use Scaffold\Config;

class ExceptionContainer extends AbstractContainer
{

    /**
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->addBuilder(new ExceptionBuilder($config, 'RuntimeException', '\RuntimeException'));
        $this->addBuilder(new ExceptionBuilder($config, 'NotFoundException', 'RuntimeException'));
        $this->addBuilder(new ExceptionBuilder($config, 'InvalidArgumentException', 'RuntimeException'));
        $this->addBuilder(new ExceptionBuilder($config, 'OutOfRangeException', 'RuntimeException'));
        $this->addBuilder(new ExceptionBuilder($config, 'OverflowException', 'RuntimeException'));
        $this->addBuilder(new ExceptionBuilder($config, 'UnexpectedValueException', 'RuntimeException'));
    }

}