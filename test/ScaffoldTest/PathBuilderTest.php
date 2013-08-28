<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace ScaffoldTest;


use Scaffold\PathBuilder;

class PathBuilderTest extends \PHPUnit_Framework_TestCase
{

    public function testAddPart()
    {
        $builder = new PathBuilder();
        $this->assertSame($builder, $builder->addPart('user'));
    }

    public function testGetModuleBase()
    {
        $builder = new PathBuilder();
        $builder->setModule('user');

        $this->assertSame('module/User', $builder->getModuleBase());
    }

    public function testGetSourcePath()
    {
        $builder = new PathBuilder();
        $builder->setModule('User');
        $builder->addPart('Group');
        $builder->addPart('Member');

        $this->assertSame('module/User/src/User/Group/Member.php', $builder->getSourcePath());
    }

    public function testGetRawPath()
    {
        $builder = new PathBuilder();
        $builder->setModule('User');
        $builder->addPart('Module');

        $this->assertSame('module/User/Module.php.dist', $builder->getRawPath('php.dist'));
    }

    public function testGetTestPath()
    {
        $builder = new PathBuilder();
        $builder->setModule('User');
        $builder->addPart('Group');
        $builder->addPart('Member');

        $this->assertSame('module/User/test/UserTest/Group/MemberTest.php', $builder->getTestPath());
    }
}
 