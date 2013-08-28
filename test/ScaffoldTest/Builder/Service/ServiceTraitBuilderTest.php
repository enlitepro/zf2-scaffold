<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace ScaffoldTest\Builder\Service;


use Scaffold\Builder\Container\ExceptionContainer;
use Scaffold\Builder\Service\ServiceBuilder;
use Scaffold\Builder\Service\ServiceTraitBuilder;
use Scaffold\Builder\SimpleContainer;
use Scaffold\Model;
use ScaffoldTest\Builder\AbstractBuilderTestCase;

class ServiceTraitBuilderTest extends AbstractBuilderTestCase
{

    public function testPrepare()
    {
        $builder = new ServiceTraitBuilder($this->getConfig());
        $state = $this->getStateMock();
        $state->expects($this->once())->method('addModel')->with(
            $this->callback(
                function (Model $model) {
                    $this->assertEquals('User\Service\GroupServiceTrait', $model->getName());
                    $this->assertEquals('module/User/src/User/Service/GroupServiceTrait.php', $model->getPath());

                    return true;
                }
            ),
            'service-trait'
        );

        $builder->prepare($state);

    }

    public function testBuild()
    {
        $state = $this->getState();
        $config = $this->getConfig();

        $prepare = new SimpleContainer();
        $prepare->addBuilder(new ServiceBuilder($config));
        $prepare->addBuilder(new ExceptionContainer($config));
        $prepare->addBuilder($builder = new ServiceTraitBuilder($config));
        $prepare->prepare($state);

        $builder->build($state);

        $fixture = file_get_contents(__DIR__ . "/fixture/trait.txt");
        $this->assertEquals($fixture, $state->getModel('service-trait')->getGenerator()->generate());
    }
}
 