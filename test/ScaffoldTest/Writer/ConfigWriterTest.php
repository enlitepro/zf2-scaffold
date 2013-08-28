<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace ScaffoldTest\Writer;


use Scaffold\Config;
use Scaffold\Writer\ConfigWriter;
use Symfony\Component\Console\Output\NullOutput;

class ConfigWriterTest extends \PHPUnit_Framework_TestCase
{

    public function testGetPath()
    {
        $config = new Config();
        $config->setModule('User');

        $configWriter = new ConfigWriter($config);
        $this->assertEquals('module/User/config/service.config.php', $configWriter->getPath());
    }

    public function testOpen()
    {
        /** @var \Scaffold\Writer\ConfigWriter|\PHPUnit_Framework_MockObject_MockObject $state */
        $writer = $this->getMockBuilder('Scaffold\Writer\ConfigWriter')
            ->disableOriginalConstructor()
            ->setMethods(['getPath'])
            ->getMock();

        $writer->expects($this->once())->method('getPath')->will($this->returnValue(__DIR__ . "/fixture/config.php"));
        $writer->open();
        $this->assertSame(array('test' => ['a' => 1]), $writer->getModuleConfig());
    }

    public function testMerge()
    {
        $configWriter = new ConfigWriter(new Config());
        $configWriter->merge(['testA' => 1]);
        $configWriter->merge(['testB' => 2]);

        $this->assertSame(['testA' => 1, 'testB' => 2], $configWriter->getModuleConfig());
    }

    public function testSave()
    {
        /** @var \Scaffold\Writer\ConfigWriter|\PHPUnit_Framework_MockObject_MockObject $state */
        $writer = $this->getMockBuilder('Scaffold\Writer\ConfigWriter')
            ->disableOriginalConstructor()
            ->setMethods(['writeData', 'getPath'])
            ->getMock();

        $config = <<<EOF
<?php

return array(
    'testA' => 1
);
EOF;


        $output = new NullOutput();
        $writer->expects($this->once())->method('getPath')->will($this->returnValue("config.php"));
        $writer->expects($this->once())->method('writeData')->with(
            "config.php",
            $config,
            $output
        );

        $writer->merge(['testA' => 1]);
        $writer->save($output);
    }

}
 