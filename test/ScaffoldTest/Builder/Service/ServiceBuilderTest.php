<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace ScaffoldTest\Builder\Service;


use Scaffold\Builder\Container\ExceptionContainer;
use Scaffold\Builder\Entity\EntityBuilder;
use Scaffold\Builder\Repository\RepositoryBuilder;
use Scaffold\Builder\Repository\RepositoryTraitBuilder;
use Scaffold\Builder\Service\ServiceBuilder;
use Scaffold\Builder\SimpleContainer;
use Scaffold\Model;
use ScaffoldTest\Builder\AbstractBuilderTestCase;

class ServiceBuilderTest extends AbstractBuilderTestCase
{

    public function testPrepare()
    {
        $builder = new ServiceBuilder($this->getConfig());
        $state = $this->getStateMock();
        $state->expects($this->once())->method('addModel')->with(
            $this->callback(
                function (Model $model) {
                    $this->assertEquals('User\Service\GroupService', $model->getName());
                    $this->assertEquals('module/User/src/User/Service/GroupService.php', $model->getPath());

                    return true;
                }
            ),
            'service'
        );

        $builder->prepare($state);

    }

    public function testBuild()
    {
        $state = $this->getState();
        $config = $this->getConfig();

        $prepare = new SimpleContainer();
        $prepare->addBuilder(new EntityBuilder($config));
        $prepare->addBuilder(new ExceptionContainer($config));
        $prepare->addBuilder(new RepositoryBuilder($config));
        $prepare->addBuilder(new RepositoryTraitBuilder($config));
        $prepare->addBuilder($builder = new ServiceBuilder($config));
        $prepare->prepare($state);

        $builder->build($state);

        $fixture = file_get_contents(__DIR__ . "/fixture/service.txt");
        $this->assertEquals($fixture, $state->getModel('service')->getGenerator()->generate());
    }
}
 