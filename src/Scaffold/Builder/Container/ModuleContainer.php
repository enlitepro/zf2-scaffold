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
        $this->addBuilder(new Module\ConfigBuilder($config, 'config/module.config.php'));
        $this->addBuilder(new Module\ConfigBuilder($config, 'config/service.config.php'));
        $this->addBuilder(new Module\ConfigBuilder($config, 'config/router.config.php'));
        $this->addBuilder(new Module\ConfigBuilder($config, 'config/navigation.config.php'));
        $this->addBuilder(new Module\ConfigBuilder($config, 'config/auth.config.php'));
        $this->addBuilder(new Module\ConfigBuilder($config, 'config/assetic.config.php'));
        $this->addBuilder(new Module\TestBuilder($config, 'test/bootstrap.php'));
        $this->addBuilder(new Module\TestBuilder($config, 'test/phpunit.xml'));
        $this->addBuilder(new Module\TestBuilder($config, 'test/TestConfig.php.dist'));
    }

}