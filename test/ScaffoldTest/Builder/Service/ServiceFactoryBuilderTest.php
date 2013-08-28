<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace ScaffoldTest\Builder\Service;


use Scaffold\Builder\Container\ExceptionContainer;
use Scaffold\Builder\Service\ServiceBuilder;
use Scaffold\Builder\Service\ServiceFactoryBuilder;
use Scaffold\Builder\SimpleContainer;
use Scaffold\Model;
use ScaffoldTest\Builder\AbstractBuilderTestCase;

class ServiceFactoryBuilderTest extends AbstractBuilderTestCase
{

    public function testPrepare()
    {
        $builder = new ServiceFactoryBuilder($this->getConfig());
        $state = $this->getStateMock();
        $state->expects($this->once())->method('addModel')->with(
            $this->callback(
                function (Model $model) {
                    $this->assertEquals('User\Service\GroupServiceFactory', $model->getName());
                    $this->assertEquals('module/User/src/User/Service/GroupServiceFactory.php', $model->getPath());
                    $this->assertSame(
                        array(
                             'service_manager' => array(
                                 'factories' => array(
                                     'UserGroupService' => 'User\Service\GroupServiceFactory'
                                 )
                             )
                        ),
                        $model->getServiceConfig()
                    );

                    return true;
                }
            ),
            'service-factory'
        );

        $builder->prepare($state);

    }

    public function testBuild()
    {
        $state = $this->getState();
        $config = $this->getConfig();

        $prepare = new SimpleContainer();
        $prepare->addBuilder(new ServiceBuilder($config));
        $prepare->addBuilder($builder = new ServiceFactoryBuilder($config));
        $prepare->prepare($state);

        $builder->build($state);

        $fixture = file_get_contents(__DIR__ . "/fixture/factory.txt");
        $this->assertEquals($fixture, $state->getModel('service-factory')->getGenerator()->generate());
    }
}
 