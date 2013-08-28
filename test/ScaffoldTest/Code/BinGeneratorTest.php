<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace ScaffoldTest\Code;


use Scaffold\Code\Generator\BinGenerator;

class BinGeneratorTest extends \PHPUnit_Framework_TestCase
{

    public function testGenerate()
    {
        $generator = new BinGenerator('Test');
        $this->assertEquals('Test', $generator->generate());
    }

}
 