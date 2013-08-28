<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace ScaffoldTest\Code;


use Scaffold\Code\Generator\TraitGenerator;

class TraitGeneratorTest extends \PHPUnit_Framework_TestCase
{
    public function testGenerate()
    {
        $generator = new TraitGenerator('Test');
        $code = <<<EOF
trait Test
{


}

EOF;

        $this->assertEquals($code, $generator->generate());
    }
}
 