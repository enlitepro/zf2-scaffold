<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace ScaffoldTest\Builder\Module;

use Scaffold\Builder\Entity\EntityBuilder;
use Scaffold\Builder\Module\ConfigBuilder;
use Scaffold\Builder\SimpleContainer;
use Scaffold\Model;
use ScaffoldTest\Builder\AbstractBuilderTestCase;

class ConfigWriterTest extends AbstractBuilderTestCase
{

    /**
     * @dataProvider configProvider
     */
    public function testPrepare($config)
    {
        $builder = new ConfigBuilder($this->getConfig(), $config);
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
        $prepare->addBuilder($builder = new ConfigBuilder($this->getConfig(), $name));
        $prepare->prepare($state);

        $builder->build($state);

        $fixture = file_get_contents(__DIR__ . "/fixture/" . basename($name, '.php') . '.txt');
        $this->assertEquals($fixture, $state->getModel($name)->getGenerator()->generate(), $name);
    }

    public function configProvider()
    {
        return array(
            ['config/module.config.php'],
            ['config/assetic.config.php'],
            ['config/auth.config.php'],
            ['config/navigation.config.php'],
            ['config/router.config.php'],
            ['config/service.config.php'],
        );
    }

}
 