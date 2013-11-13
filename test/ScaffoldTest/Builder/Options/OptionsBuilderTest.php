<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace ScaffoldTest\Builder\Options;


use Scaffold\Builder\Entity\EntityBuilder;
use Scaffold\Builder\Options\OptionsBuilder;
use Scaffold\Builder\SimpleContainer;
use Scaffold\Model;
use ScaffoldTest\Builder\AbstractBuilderTestCase;

class OptionsBuilderTest extends AbstractBuilderTestCase
{

    public function testPrepare()
    {
        $builder = new OptionsBuilder($this->getConfig());
        $state = $this->getStateMock();
        $state->expects($this->once())->method('addModel')->with(
            $this->callback(
                function (Model $model) {
                    $this->assertEquals('User\GroupOptions', $model->getName());
                    $this->assertEquals('module/User/src/User/GroupOptions.php', $model->getPath());

                    return true;
                }
            ),
            'options'
        );

        $builder->prepare($state);

    }

    public function testBuild()
    {
        $state = $this->getState();
        $config = $this->getConfig();

        $prepare = new SimpleContainer();
        $prepare->addBuilder($builder = new OptionsBuilder($config));
        $prepare->prepare($state);

        $builder->build($state);

        $fixture = file_get_contents(__DIR__ . "/fixture/options.txt");
        $this->assertEquals($fixture, $state->getModel('options')->getGenerator()->generate());
    }
} 