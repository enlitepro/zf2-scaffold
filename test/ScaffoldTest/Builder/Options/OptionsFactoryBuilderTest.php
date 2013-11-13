<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace ScaffoldTest\Builder\Options;


use Scaffold\Builder\Entity\EntityBuilder;
use Scaffold\Builder\Options\OptionsBuilder;
use Scaffold\Builder\Options\OptionsFactoryBuilder;
use Scaffold\Builder\SimpleContainer;
use Scaffold\Model;
use ScaffoldTest\Builder\AbstractBuilderTestCase;

class OptionsFactoryBuilderTest extends AbstractBuilderTestCase
{

    public function testPrepare()
    {
        $builder = new OptionsFactoryBuilder($this->getConfig());
        $state = $this->getStateMock();
        $state->expects($this->once())->method('addModel')->with(
            $this->callback(
                function (Model $model) {
                    $this->assertEquals('User\GroupOptionsFactory', $model->getName());
                    $this->assertEquals('module/User/src/User/GroupOptionsFactory.php', $model->getPath());
                    $this->assertSame(
                        array(
                             'service_manager' => array(
                                 'factories' => array(
                                     'UserGroupOptions' => 'User\GroupOptionsFactory'
                                 )
                             )
                        ),
                        $model->getServiceConfig()
                    );

                    return true;
                }
            ),
            'options-factory'
        );

        $builder->prepare($state);

    }

    public function testBuild()
    {
        $state = $this->getState();
        $config = $this->getConfig();

        $prepare = new SimpleContainer();
        $prepare->addBuilder(new EntityBuilder($config));
        $prepare->addBuilder(new OptionsBuilder($config));
        $prepare->addBuilder($builder = new OptionsFactoryBuilder($config));
        $prepare->prepare($state);

        $builder->build($state);

        $fixture = file_get_contents(__DIR__ . "/fixture/factory.txt");
        $this->assertEquals($fixture, $state->getModel('options-factory')->getGenerator()->generate());
    }
} 