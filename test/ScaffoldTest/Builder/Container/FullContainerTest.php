<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace ScaffoldTest\Builder\Container;


use Scaffold\Builder\Container\FullContainer;
use ScaffoldTest\Builder\AbstractBuilderTestCase;

class FullContainerTest extends AbstractBuilderTestCase
{

    public function testConstruct()
    {
        $container = new FullContainer($this->getConfig());
        $builders = $container->getBuilders();
        $i = 0;
        $this->assertInstanceOf('Scaffold\Builder\Service\ServiceBuilder', $builders[$i++]);
        $this->assertInstanceOf('Scaffold\Builder\Service\ServiceFactoryBuilder', $builders[$i++]);
        $this->assertInstanceOf('Scaffold\Builder\Service\ServiceTraitBuilder', $builders[$i++]);
        $this->assertInstanceOf('Scaffold\Builder\Service\ServiceTestBuilder', $builders[$i++]);
        $this->assertInstanceOf('Scaffold\Builder\Entity\EntityBuilder', $builders[$i++]);
        $this->assertInstanceOf('Scaffold\Builder\Entity\EntityTestBuilder', $builders[$i++]);
        $this->assertInstanceOf('Scaffold\Builder\Repository\RepositoryBuilder', $builders[$i++]);
        $this->assertInstanceOf('Scaffold\Builder\Repository\RepositoryTraitBuilder', $builders[$i++]);
        $this->assertInstanceOf('Scaffold\Builder\Controller\ControllerBuilder', $builders[$i++]);
        $this->assertInstanceOf('Scaffold\Builder\Form\FormFactoryBuilder', $builders[$i++]);
        $this->assertInstanceOf('Scaffold\Builder\Container\ExceptionContainer', $builders[$i++]);
    }

}
 