<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace ScaffoldTest\Builder\Container;


use Scaffold\Builder\Container\OptionsContainer;
use ScaffoldTest\Builder\AbstractBuilderTestCase;

class OptionsContainerTest extends AbstractBuilderTestCase
{

    public function testConstruct()
    {
        $container = new OptionsContainer($this->getConfig());
        $builders = $container->getBuilders();
        $this->assertCount(3, $builders);
    }

}
 