<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace ScaffoldTest\Builder\Controller;


use Scaffold\Builder\Controller\ControllerBuilder;
use Scaffold\Builder\Entity\EntityBuilder;
use Scaffold\Builder\Form\FormFactoryBuilder;
use Scaffold\Builder\Service\ServiceBuilder;
use Scaffold\Builder\Service\ServiceTraitBuilder;
use Scaffold\Builder\SimpleContainer;
use Scaffold\Model;
use ScaffoldTest\Builder\AbstractBuilderTestCase;

class ControllerBuilderTest extends AbstractBuilderTestCase
{

    public function testPrepare()
    {
        $builder = new ControllerBuilder($this->getConfig());
        $state = $this->getStateMock();
        $state->expects($this->once())->method('addModel')->with(
            $this->callback(
                function (Model $model) {
                    $this->assertEquals('User\Controller\GroupController', $model->getName());
                    $this->assertEquals('module/User/src/User/Controller/GroupController.php', $model->getPath());
                    $this->assertSame(
                        array(
                             'controllers' => array(
                                 'invokables' => array(
                                     'UserGroup' => 'User\Controller\GroupController'
                                 )
                             )
                        ),
                        $model->getServiceConfig()
                    );

                    return true;
                }
            ),
            'controller'
        );

        $builder->prepare($state);
    }

    public function testBuild()
    {
        $state = $this->getState();
        $config = $this->getConfig();

        $prepare = new SimpleContainer();
        $prepare->addBuilder(new EntityBuilder($config));
        $prepare->addBuilder(new ServiceBuilder($config));
        $prepare->addBuilder(new ServiceTraitBuilder($config));
        $prepare->addBuilder(new FormFactoryBuilder($config));
        $prepare->addBuilder($builder = new ControllerBuilder($config));
        $prepare->prepare($state);

        $builder->build($state);

        $fixture = file_get_contents(__DIR__ . "/fixture/controller.txt");
        $this->assertEquals($fixture, $state->getModel('controller')->getGenerator()->generate());
    }
}
 