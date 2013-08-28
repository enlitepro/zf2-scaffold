<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace ScaffoldTest\Writer;


use Scaffold\Code\Generator\BinGenerator;
use Scaffold\Code\Generator\ValueGenerator;
use Scaffold\Config;
use Scaffold\Model;
use Scaffold\State;
use Scaffold\Writer\ConfigWriter;
use Scaffold\Writer\ModelWriter;
use Symfony\Component\Console\Output\NullOutput;
use Zend\Code\Generator\ClassGenerator;

class ModelWriterTest extends \PHPUnit_Framework_TestCase
{

    public function testMergeConfig()
    {
        $writer = new ModelWriter(new Config());
        $model = new Model();
        $model->setServiceConfig(array('test' => 1));

        $state = new State(new ConfigWriter(new Config()));
        $writer->mergeConfig($state, $model, new NullOutput());

        $this->assertSame(['test' => 1], $state->getModuleConfig()->getModuleConfig());
    }

    public function testWriteModel()
    {
        $writer = $this->getWriter(['writeData']);


        $model = new Model();
        $model->setPath('Model.php');
        $model->setGenerator(new ClassGenerator('Test'));

        $data
            = <<<EOF
<?php

class Test
{


}

EOF;

        $nullOutput = new NullOutput();
        $writer->expects($this->once())->method('writeData')->with('Model.php', $data, $nullOutput);

        $writer->writeModel($model, $nullOutput);
    }

    public function testWriteModelBin()
    {
        $writer = $this->getWriter(['writeData']);


        $model = new Model();
        $model->setPath('Model.php');
        $model->setGenerator(new BinGenerator('Test'));

        $nullOutput = new NullOutput();
        $writer->expects($this->once())->method('writeData')->with('Model.php', 'Test', $nullOutput);

        $writer->writeModel($model, $nullOutput);
    }

    public function testWrite()
    {
        $state = new State(new ConfigWriter(new Config()));
        $modelA = new Model();
        $modelB = new Model();
        $state->addModel($modelA);
        $state->addModel($modelB);

        $output = new NullOutput();

        $writer = $this->getWriter(['writeModel', 'mergeConfig']);
        $writer->expects($this->at(0))->method('writeModel')->with($modelA, $output);
        $writer->expects($this->at(1))->method('mergeConfig')->with($state, $modelA, $output);
        $writer->expects($this->at(2))->method('writeModel')->with($modelB, $output);
        $writer->expects($this->at(3))->method('mergeConfig')->with($state, $modelB, $output);

        $writer->write($state, $output);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    public function getWriter(array $methods)
    {
        /** @var \Scaffold\Writer\ModelWriter|\PHPUnit_Framework_MockObject_MockObject $state */
        $writer = $this->getMockBuilder('Scaffold\Writer\ModelWriter')
            ->disableOriginalConstructor()
            ->setMethods($methods)
            ->getMock();
        return $writer;
    }

}
 