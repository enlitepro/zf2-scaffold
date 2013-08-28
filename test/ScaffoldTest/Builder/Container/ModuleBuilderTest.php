<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace ScaffoldTest\Builder\Container;


use Scaffold\Builder\Container\ModuleContainer;
use ScaffoldTest\Builder\AbstractBuilderTestCase;

class ModuleBuilderTest extends AbstractBuilderTestCase
{

    public function testConstruct()
    {
        $container = new ModuleContainer($this->getConfig());
        $builders = $container->getBuilders();
        $this->assertCount(10, $builders);
    }
}
 