<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace ScaffoldTest\Builder\Module;

use Scaffold\Builder\Entity\EntityBuilder;
use Scaffold\Builder\Module\ConfigBuilder;
use Scaffold\Builder\Module\TestBuilder;
use Scaffold\Builder\SimpleContainer;
use Scaffold\Model;
use ScaffoldTest\Builder\AbstractBuilderTestCase;

class TestBuilderTest extends AbstractBuilderTestCase
{

    /**
     * @dataProvider configProvider
     */
    public function testPrepare($config)
    {
        $builder = new TestBuilder($this->getConfig(), $config);
        $state = $this->getStateMock();
        $state->expects($this->once())->method('addModel')->with(
            $this->callback(
                function (Model $model) use ($config) {
                    $this->assertEquals('module/User/' . $config, $model->getPath());
                    return true;
                }
            ),
            $config
        );

        $builder->prepare($state);

    }

    /**
     * @dataProvider configProvider
     */
    public function testBuild($name)
    {
        $state = $this->getState();
        $config = $this->getConfig();

        $prepare = new SimpleContainer();
        $prepare->addBuilder(new EntityBuilder($config));
        $prepare->addBuilder($builder = new TestBuilder($this->getConfig(), $name));
        $prepare->prepare($state);

        $builder->build($state);

        $fixture = file_get_contents(__DIR__ . "/fixture/" . basename($name, '.php') . '.txt');
        $this->assertEquals($fixture, $state->getModel($name)->getGenerator()->generate(), $name);
    }

    public function configProvider()
    {
        return array(
            ['test/bootstrap.php'],
            ['test/phpunit.xml'],
            ['test/TestConfig.php.dist'],
        );
    }
}
 