<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace ScaffoldTest\Builder\Entity;


use Scaffold\Builder\Entity\EntityBuilder;
use Scaffold\Builder\Entity\EntityTestBuilder;
use Scaffold\Builder\SimpleContainer;
use Scaffold\Model;
use ScaffoldTest\Builder\AbstractBuilderTestCase;

class EntityTestBuilderTest extends AbstractBuilderTestCase
{

    public function testPrepare()
    {
        $builder = new EntityTestBuilder($this->getConfig());
        $state = $this->getStateMock();
        $state->expects($this->once())->method('addModel')->with(
            $this->callback(
                function (Model $model) {
                    $this->assertEquals('UserTest\Entity\GroupTest', $model->getName());
                    $this->assertEquals('module/User/test/UserTest/Entity/GroupTest.php', $model->getPath());

                    return true;
                }
            ),
            'entity-test'
        );

        $builder->prepare($state);
    }

    public function testBuild()
    {
        $state = $this->getState();
        $config = $this->getConfig();

        $prepare = new SimpleContainer();
        $prepare->addBuilder(new EntityBuilder($config));
        $prepare->addBuilder($builder = new EntityTestBuilder($config));
        $prepare->prepare($state);

        $builder->build($state);

        $fixture = file_get_contents(__DIR__ . "/fixture/test.txt");
        $this->assertEquals($fixture, $state->getModel('entity-test')->getGenerator()->generate());
    }
}
 