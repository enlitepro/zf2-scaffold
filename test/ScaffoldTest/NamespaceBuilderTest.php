<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace ScaffoldTest;


use Scaffold\NamespaceBuilder;

class NamespaceBuilderTest extends \PHPUnit_Framework_TestCase
{

    public function testAddPart()
    {
        $builder = new NamespaceBuilder();
        $this->assertSame($builder, $builder->addPart('user'));
    }

    public function testGetNamespace()
    {
        $builder = new NamespaceBuilder();
        $builder->addPart('user')->addPart('Entity')->addPart('usEr');
        $this->assertEquals('User\Entity\UsEr', $builder->getNamespace());
    }

}
 