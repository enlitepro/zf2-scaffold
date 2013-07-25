<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace Scaffold\Builder;


use Scaffold\Entity\Config;
use Scaffold\Builder\Module;

class ModuleBuilder extends Container
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