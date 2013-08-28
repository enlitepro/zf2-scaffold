<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace ScaffoldTest\Builder\Module;

use Scaffold\Builder\Entity\EntityBuilder;
use Scaffold\Builder\Module\ConfigBuilder;
use Scaffold\Builder\Module\ModuleBuilder;
use Scaffold\Builder\SimpleContainer;
use Scaffold\Model;
use ScaffoldTest\Builder\AbstractBuilderTestCase;

class ModuleBuilderTest extends AbstractBuilderTestCase
{

    public function testPrepare()
    {
        $builder = new ModuleBuilder($this->getConfig());
        $state = $this->getStateMock();
        $state->expects($this->once())->method('addModel')->with(
            $this->callback(
                function (Model $model) {
                    $this->assertEquals('module/User/Module.php', $model->getPath());
                    $this->assertEquals('User\Module', $model->getName());
                    return true;
                }
            ),
            'module'
        );

        $builder->prepare($state);

    }

    public function testBuild()
    {
        $state = $this->getState();
        $config = $this->getConfig();

        $prepare = new SimpleContainer();
        $prepare->addBuilder(new EntityBuilder($config));
        $prepare->addBuilder($builder = new ModuleBuilder($config));
        $prepare->prepare($state);

        $builder->build($state);

        $fixture = file_get_contents(__DIR__ . "/fixture/module.txt");
        $this->assertEquals($fixture, $state->getModel('module')->getGenerator()->generate());
    }
}
 