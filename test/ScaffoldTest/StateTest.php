<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace ScaffoldTest;


use Scaffold\Model;
use Scaffold\State;

class StateTest extends \PHPUnit_Framework_TestCase
{

    public function testAddModel()
    {
        $state = new State($this->getConfigWriter());

        $modelA = new Model();
        $modelB = new Model();

        $state->addModel($modelA);
        $state->addModel($modelB, 'B');

        $this->assertEquals([0 => $modelA, 'B' => $modelB], $state->getModels());
    }

    public function testGetModel()
    {
        $state = new State($this->getConfigWriter());

        $model = new Model();
        $state->addModel($model, 'test');

        $this->assertSame($model, $state->getModel('test'));
    }

    /**
     * @expectedException \Scaffold\Exception\RuntimeException
     */
    public function testGetModelNotFound()
    {
        $state = new State($this->getConfigWriter());
        $state->getModel('test');
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    public function getConfigWriter()
    {
        /** @var \Scaffold\Writer\ConfigWriter|\PHPUnit_Framework_MockObject_MockObject $state */
        $writer = $this->getMockBuilder('Scaffold\Writer\ConfigWriter')
            ->disableOriginalConstructor()
            ->getMock();
        return $writer;
    }

}
 