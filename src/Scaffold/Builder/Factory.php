<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace Scaffold\Builder;



use Scaffold\Config;

class Factory
{

    /**
     * Factory builder container
     */
    public function factory(Config $config)
    {
        $builder = new Container();
        $builder->addBuilder(new EntityBuilder($config));
        $builder->addBuilder(new RepositoryBuilder($config));
        $builder->addBuilder(new ServiceBuilder($config));
        $builder->addBuilder(new RuntimeExceptionBuilder($config));
        $builder->addBuilder(new NotFoundExceptionBuilder($config));
        $builder->addBuilder(new ControllerBuilder($config));
        $builder->addBuilder(new FormFactoryBuilder($config));
        $builder->addBuilder(new ServiceTraitBuilder($config));
        $builder->addBuilder(new EntityTestBuilder($config));
        $builder->addBuilder(new ServiceTestBuilder($config));

        return $builder;
    }

}