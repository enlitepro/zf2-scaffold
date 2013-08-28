<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace ScaffoldTest\Builder\Container;


use Scaffold\Builder\Container\ExceptionContainer;
use ScaffoldTest\Builder\AbstractBuilderTestCase;

class ExceptionContainerTest extends AbstractBuilderTestCase
{

    public function testConstruct()
    {
        $container = new ExceptionContainer($this->getConfig());
        $builders = $container->getBuilders();
        $this->assertCount(6, $builders);
    }

}
 