<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace Scaffold\Builder\Container;


use Scaffold\Builder\AbstractContainer;
use Scaffold\Builder\Controller;
use Scaffold\Builder\Entity;
use Scaffold\Builder\Exception;
use Scaffold\Builder\Form;
use Scaffold\Builder\Repository;
use Scaffold\Builder\Service;
use Scaffold\Config;

class FullContainer extends AbstractContainer
{

    /**
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->addBuilder(new Service\ServiceBuilder($config));
        $this->addBuilder(new Service\ServiceFactoryBuilder($config));
        $this->addBuilder(new Service\ServiceTraitBuilder($config));
        $this->addBuilder(new Service\ServiceTestBuilder($config));
        $this->addBuilder(new Entity\EntityBuilder($config));
        $this->addBuilder(new Entity\EntityTestBuilder($config));
        $this->addBuilder(new Repository\RepositoryBuilder($config));
        $this->addBuilder(new Repository\RepositoryTraitBuilder($config));
        $this->addBuilder(new Controller\ControllerBuilder($config));
        $this->addBuilder(new Form\FormFactoryBuilder($config));
        $this->addBuilder(new ExceptionContainer($config));
    }

}