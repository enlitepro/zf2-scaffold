<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace Scaffold\Builder\Container;


use Scaffold\Builder\AbstractContainer;
use Scaffold\Config;
use Scaffold\Builder\Module;

class ModuleContainer extends AbstractContainer
{

    /**
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->addBuilder(new Module\ModuleBuilder($config));
        $this->addBuilder(new Module\ConfigBuilder($config, 'module.config.php'));
        $this->addBuilder(new Module\ConfigBuilder($config, 'service.config.php'));
        $this->addBuilder(new Module\ConfigBuilder($config, 'router.config.php'));
        $this->addBuilder(new Module\ConfigBuilder($config, 'navigation.config.php'));
        $this->addBuilder(new Module\ConfigBuilder($config, 'auth.config.php'));
        $this->addBuilder(new Module\ConfigBuilder($config, 'assetic.config.php'));
    }

}