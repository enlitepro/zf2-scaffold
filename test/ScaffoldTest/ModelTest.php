<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace ScaffoldTest;


use Scaffold\Model;

class ModelTest extends \PHPUnit_Framework_TestCase
{

    public function testGetClassname()
    {
        $model = new Model();
        $model->setName('User\Entity\Group');
        $this->assertEquals('Group', $model->getClassName());
    }

    public function testGetServiceName()
    {
        $model = new Model();
        $model->setName('User\Service\GroupService');
        $this->assertEquals('UserGroupService', $model->getServiceName());
    }

    public function testGetServiceNameWhenModuleSameName()
    {
        $model = new Model();
        $model->setName('User\Service\UserService');
        $this->assertEquals('UserService', $model->getServiceName());
    }

    public function testGetServiceNameForForm()
    {
        $model = new Model();
        $model->setName('User\Form\GroupFormFactory');
        $this->assertEquals('UserGroupForm', $model->getServiceName());
    }

    public function testGetServiceNameForFormWhenModuleSameName()
    {
        $model = new Model();
        $model->setName('User\Form\UserFormFactory');
        $this->assertEquals('UserForm', $model->getServiceName());
    }

    public function testGetServiceNameForController()
    {
        $model = new Model();
        $model->setName('User\Controller\IndexController');
        $this->assertEquals('UserIndex', $model->getServiceName());
    }

    public function testGetServiceNameForControllerWhenModuleSameName()
    {
        $model = new Model();
        $model->setName('User\Controller\UserController');
        $this->assertEquals('UserUser', $model->getServiceName());
    }

}
 