<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace Scaffold\Builder;


use Scaffold\AbstractConfig;

class Factory
{

    /**
     * Factory builder container
     */
    public function factory(AbstractConfig $config)
    {
        $builder = new Container();
        $builder->addBuilder(new EntityBuilder($config));
        $builder->addBuilder(new RepositoryBuilder($config));
        $builder->addBuilder(new ServiceBuilder($config));
        $builder->addBuilder(new ExceptionBuilder($config));
        $builder->addBuilder(new ControllerBuilder($config));
        $builder->addBuilder(new FormFactoryBuilder($config));
        $builder->addBuilder(new ServiceTraitBuilder($config));
        $builder->addBuilder(new EntityTestBuilder($config));
        $builder->addBuilder(new ServiceTestBuilder($config));

        return $builder;
    }

}