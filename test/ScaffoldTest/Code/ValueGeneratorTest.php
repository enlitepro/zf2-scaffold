<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace ScaffoldTest\Code;


use Scaffold\Code\Generator\ValueGenerator;

class ValueGeneratorTest extends \PHPUnit_Framework_TestCase
{
    public function testGenerate()
    {
        $generator = new ValueGenerator(array('a' => array('b' => 1)));
        $code = <<<EOF
array(
        'a' => array(
            'b' => 1
        )
    )
EOF;
        $this->assertEquals($code, $generator->generate());
    }
}
 