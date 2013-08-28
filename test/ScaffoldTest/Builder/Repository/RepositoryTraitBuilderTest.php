<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace ScaffoldTest\Builder\Repository;


use Scaffold\Builder\Entity\EntityBuilder;
use Scaffold\Builder\Repository\RepositoryBuilder;
use Scaffold\Builder\Repository\RepositoryTraitBuilder;
use Scaffold\Builder\SimpleContainer;
use Scaffold\Model;
use ScaffoldTest\Builder\AbstractBuilderTestCase;

class RepositoryTraitBuilderTest extends AbstractBuilderTestCase
{

    public function testPrepare()
    {
        $builder = new RepositoryTraitBuilder($this->getConfig());
        $state = $this->getStateMock();
        $state->expects($this->once())->method('addModel')->with(
            $this->callback(
                function (Model $model) {
                    $this->assertEquals('User\Repository\GroupRepositoryTrait', $model->getName());
                    $this->assertEquals('module/User/src/User/Repository/GroupRepositoryTrait.php', $model->getPath());

                    return true;
                }
            ),
            'repository-trait'
        );

        $builder->prepare($state);

    }

    public function testBuild()
    {
        $state = $this->getState();
        $config = $this->getConfig();

        $prepare = new SimpleContainer();
        $prepare->addBuilder(new EntityBuilder($config));
        $prepare->addBuilder(new RepositoryBuilder($config));
        $prepare->addBuilder($builder = new RepositoryTraitBuilder($config));
        $prepare->prepare($state);

        $builder->build($state);

        $fixture = file_get_contents(__DIR__ . "/fixture/trait.txt");
        $this->assertEquals($fixture, $state->getModel('repository-trait')->getGenerator()->generate());
    }
}
 