<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace Scaffold\Builder\Container;


use Scaffold\Builder\AbstractContainer;
use Scaffold\Builder\Options;
use Scaffold\Config;

class OptionsContainer extends AbstractContainer
{

    /**
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->addBuilder(new Options\OptionsBuilder($config));
        $this->addBuilder(new Options\OptionsFactoryBuilder($config));
        $this->addBuilder(new Options\OptionsTraitBuilder($config));
    }

} 