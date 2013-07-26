<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace Scaffold\Builder;


use Scaffold\Config;

class EntityContainer extends Container
{

    /**
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->addBuilder(new EntityBuilder($config));
        $this->addBuilder(new RepositoryBuilder($config));
        $this->addBuilder(new ServiceBuilder($config));
        $this->addBuilder(new RuntimeExceptionBuilder($config));
        $this->addBuilder(new NotFoundExceptionBuilder($config));
        $this->addBuilder(new ControllerBuilder($config));
        $this->addBuilder(new FormFactoryBuilder($config));
        $this->addBuilder(new ServiceTraitBuilder($config));
        $this->addBuilder(new EntityTestBuilder($config));
        $this->addBuilder(new ServiceTestBuilder($config));
    }

}