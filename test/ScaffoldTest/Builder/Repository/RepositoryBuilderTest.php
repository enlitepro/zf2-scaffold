<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace ScaffoldTest\Builder\Repository;


use Scaffold\Builder\Entity\EntityBuilder;
use Scaffold\Builder\Repository\RepositoryBuilder;
use Scaffold\Builder\SimpleContainer;
use Scaffold\Model;
use ScaffoldTest\Builder\AbstractBuilderTestCase;

class RepositoryBuilderTest extends AbstractBuilderTestCase
{

    public function testPrepare()
    {
        $builder = new RepositoryBuilder($this->getConfig());
        $state = $this->getStateMock();
        $state->expects($this->once())->method('addModel')->with(
            $this->callback(
                function (Model $model) {
                    $this->assertEquals('User\Repository\GroupRepository', $model->getName());
                    $this->assertEquals('module/User/src/User/Repository/GroupRepository.php', $model->getPath());

                    return true;
                }
            ),
            'repository'
        );

        $builder->prepare($state);

    }

    public function testBuild()
    {
        $state = $this->getState();
        $config = $this->getConfig();

        $prepare = new SimpleContainer();
        $prepare->addBuilder(new EntityBuilder($config));
        $prepare->addBuilder($builder = new RepositoryBuilder($config));
        $prepare->prepare($state);

        $builder->build($state);

        $fixture = file_get_contents(__DIR__ . "/fixture/repository.txt");
        $this->assertEquals($fixture, $state->getModel('repository')->getGenerator()->generate());
    }
}
 