<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace ScaffoldTest\Builder\Entity;

use Scaffold\Builder\Entity\EntityBuilder;
use Scaffold\Builder\Repository\RepositoryBuilder;
use Scaffold\Builder\SimpleContainer;
use Scaffold\Model;
use ScaffoldTest\Builder\AbstractBuilderTestCase;

class EntityBuilderTest extends AbstractBuilderTestCase
{

    public function testPrepare()
    {
        $builder = new EntityBuilder($this->getConfig());
        $state = $this->getStateMock();
        $state->expects($this->once())->method('addModel')->with(
            $this->callback(
                function (Model $model) {
                    $this->assertEquals('User\Entity\Group', $model->getName());
                    $this->assertEquals('module/User/src/User/Entity/Group.php', $model->getPath());

                    return true;
                }
            ),
            'entity'
        );

        $builder->prepare($state);
    }

    public function testBuild()
    {
        $state = $this->getState();
        $config = $this->getConfig();

        $prepare = new SimpleContainer();
        $prepare->addBuilder(new RepositoryBuilder($config));
        $prepare->addBuilder($builder = new EntityBuilder($config));
        $prepare->prepare($state);

        $builder->build($state);

        $fixture = file_get_contents(__DIR__ . "/fixture/entity.txt");
        $this->assertEquals($fixture, $state->getModel('entity')->getGenerator()->generate());
    }

}
 