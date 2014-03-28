<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace ScaffoldTest\Builder\Options;


use Scaffold\Builder\Container\ExceptionContainer;
use Scaffold\Builder\Entity\EntityBuilder;
use Scaffold\Builder\Options\OptionsBuilder;
use Scaffold\Builder\Options\OptionsTraitBuilder;
use Scaffold\Builder\SimpleContainer;
use Scaffold\Model;
use ScaffoldTest\Builder\AbstractBuilderTestCase;

class OptionsTraitBuilderTest extends AbstractBuilderTestCase
{

    public function testPrepare()
    {
        $builder = new OptionsTraitBuilder($this->getConfig());
        $state = $this->getStateMock();
        $state->expects($this->once())->method('addModel')->with(
            $this->callback(
                function (Model $model) {
                    $this->assertEquals('User\GroupOptionsTrait', $model->getName());
                    $this->assertEquals('module/User/src/User/GroupOptionsTrait.php', $model->getPath());

                    return true;
                }
            ),
            'options-trait'
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
        $prepare->addBuilder(new ExceptionContainer($config));
        $prepare->addBuilder($builder = new OptionsTraitBuilder($config));
        $prepare->prepare($state);

        $builder->build($state);

        $fixture = file_get_contents(__DIR__ . "/fixture/trait.txt");
        $this->assertEquals($fixture, $state->getModel('options-trait')->getGenerator()->generate());
    }
}
 