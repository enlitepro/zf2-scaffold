<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace ScaffoldTest\Code;


use Scaffold\Code\Generator\ClassGenerator;

class ClassGeneratorTest extends \PHPUnit_Framework_TestCase
{
    public function testGenerate()
    {
        $generator = new ClassGenerator('Abc\Test');
        $generator->setAbstract(true);
        $generator->addTrait('ATrait');
        $generator->addUse('ATrait');
        $generator->addTrait('BTrait');
        $generator->setImplementedInterfaces(['ITestA', 'ITestB']);
        $generator->setExtendedClass('ParentClass');
        $generator->addProperty('abc');
        $generator->addMethod('bar');

        $data = <<<EOF
namespace Abc;

use ATrait;

abstract class Test extends ParentClass implements ITestA, ITestB
{

    use ATrait,
        BTrait;

    public \$abc = null;

    public function bar()
    {
    }


}

EOF;


        $this->assertEquals($data, $generator->generate());
    }
}
 