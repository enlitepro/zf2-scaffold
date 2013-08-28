<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace ScaffoldTest\Code;


use Scaffold\Code\Generator\RawGenerator;

class RawGeneratorTest extends \PHPUnit_Framework_TestCase
{
    public function testGenerate()
    {
        $generator = new RawGenerator('Test');
        $this->assertEquals('Test', $generator->generate());
    }
}
 