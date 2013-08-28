<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace Scaffold\Builder\Service;


use Scaffold\Builder\AbstractBuilder;
use Scaffold\State;
use Scaffold\Code\Generator\ClassGenerator;
use Scaffold\Config;
use Scaffold\Model;
use Zend\Code\Generator\MethodGenerator;
use Zend\Code\Generator\ParameterGenerator;

class ServiceTestBuilder extends AbstractBuilder
{
    /**
     * @var \Scaffold\Config
     */
    protected $config;

    /**
     * @var Model
     */
    protected $model;

    /**
     * @param State|State $state
     */
    public function prepare(State $state)
    {
        $model = new Model();
        $name = $this->buildNamespace()
            ->addPart($this->config->getModule() . 'Test')
            ->addPart('Service')
            ->addPart($this->config->getName() . 'ServiceTest')
            ->getNamespace();

        $path = $this->buildPath()
            ->setModule($this->config->getModule())
            ->addPart('Service')
            ->addPart($this->config->getName() . 'Service')
            ->getTestPath();

        $model->setName($name);
        $model->setPath($path);
        $state->addModel($model, 'service-test');

        $this->model = $model;
    }


    /**
     * @param State|State $state
     * @return State|void
     */
    public function build(State $state)
    {
        $model = $this->model;
        $generator = new ClassGenerator($model->getName());
        $model->setGenerator($generator);

        $generator->setExtendedClass('\PHPUnit_Framework_TestCase');
        $generator->addUse($state->getServiceModel()->getName());
        $generator->addUse($state->getEntityModel()->getName());
        $generator->addUse($state->getRepositoryModel()->getName());
        $generator->addUse('Zend\ServiceManager\ServiceManager');
        $generator->addUse('Doctrine\ORM\EntityManager');


        $this->addLoadById($generator, $state);
        $this->addSearch($generator, $state);
        $this->addSave($generator, $state);
        $this->addDelete($generator, $state);

        $this->addGetObject($generator, $state);
        $this->addGetRepository($generator, $state);
        $this->addGetEntityManager($generator, $state);
        $this->addGetServiceManager($generator, $state);
    }

    /**
     * @param ClassGenerator $generator
     * @param State $state
     */
    public function addLoadById(ClassGenerator $generator, State $state)
    {
        $entity = $state->getEntityModel()->getClassName();
        $repository = ucfirst($state->getRepositoryModel()->getClassName());

        $code
            = <<<EOF
\$id = 123;
\$entity = new $entity();

\$object = \$this->getObject();
\$object->get$repository()->expects(\$this->once())->method('find')
    ->with(\$id)->will(\$this->returnValue(\$entity));

\$this->assertSame(\$entity, \$object->loadById(\$id));
EOF;
        $generator->addMethodFromGenerator(
            new MethodGenerator('testLoadById', [], MethodGenerator::FLAG_PUBLIC, $code)
        );


        $doc = '@expectedException \\' . $state->getModel('NotFoundException')->getName();
        $code
            = <<<EOF
\$id = 123;

\$object = \$this->getObject();
\$object->get$repository()->expects(\$this->once())->method('find')
    ->with(\$id)->will(\$this->returnValue(false));

\$object->loadById(\$id);
EOF;

        $generator->addMethodFromGenerator(
            new MethodGenerator('testLoadByIdFailed', [], MethodGenerator::FLAG_PUBLIC, $code, $doc)
        );
    }

    public function addSearch(ClassGenerator $generator, State $state)
    {
        $entity = $state->getEntityModel()->getClassName();
        $repository = ucfirst($state->getRepositoryModel()->getClassName());

        $code
            = <<<EOF
\$criteria = ['id' => 123];
\$result = [new $entity()];

\$object = \$this->getObject();
\$object->get$repository()->expects(\$this->once())->method('findBy')
    ->with(\$criteria)->will(\$this->returnValue(\$result));

\$this->assertSame(\$result, \$object->search(\$criteria));
EOF;
        $generator->addMethodFromGenerator(
            new MethodGenerator('testSearch', [], MethodGenerator::FLAG_PUBLIC, $code)
        );
    }

    /**
     * @param ClassGenerator $generator
     * @param State $state
     */
    public function addSave(ClassGenerator $generator, State $state)
    {
        $entity = $state->getEntityModel()->getClassName();

        $code
            = <<<EOF
\$entity = new $entity();

\$object = \$this->getObject();
\$object->getEntityManager()->expects(\$this->once())->method('persist')->with(\$entity);
\$object->save(\$entity);
EOF;
        $generator->addMethodFromGenerator(
            new MethodGenerator('testSave', [], MethodGenerator::FLAG_PUBLIC, $code)
        );
    }

    /**
     * @param ClassGenerator $generator
     * @param State $state
     */
    public function addDelete(ClassGenerator $generator, State $state)
    {
        $entity = $state->getEntityModel()->getClassName();

        $code
            = <<<EOF
\$entity = new $entity();

\$object = \$this->getObject();
\$object->getEntityManager()->expects(\$this->once())->method('remove')->with(\$entity);
\$object->delete(\$entity);
EOF;
        $generator->addMethodFromGenerator(
            new MethodGenerator('testDelete', [], MethodGenerator::FLAG_PUBLIC, $code)
        );
    }

    /**
     * @param ClassGenerator $generator
     * @param \Scaffold\State $state
     */
    public function addGetServiceManager(ClassGenerator $generator, State $state)
    {
        $doc = '@return \PHPUnit_Framework_MockObject_MockObject|ServiceManager';
        $body = 'return $this->getMock(\'Zend\ServiceManager\ServiceManager\');';

        $method = new MethodGenerator('getServiceManager', [], MethodGenerator::FLAG_PUBLIC, $body, $doc);
        $generator->addMethodFromGenerator($method);
    }

    /**
     * @param ClassGenerator $generator
     * @param State $state
     */
    public function addGetObject(ClassGenerator $generator, State $state)
    {
        $className = $state->getServiceModel()->getClassName();
        $doc = "@param array \$methods\n@return \\PHPUnit_Framework_MockObject_MockObject|" . $className;

        $class = $state->getServiceModel()->getName();
        $repository = ucfirst($state->getRepositoryModel()->getClassName());

        $body
            = <<<EOF
if (count(\$methods)) {
    \$object = \$this->getMockBuilder('$class')
        ->disableOriginalConstructor()
        ->setMethods(\$methods)
        ->getMock();
}
else {
    \$object = new $className(\$this->getServiceManager());
}

\$object->set$repository(\$this->getRepository());
\$object->setEntityManager(\$this->getEntityManager());

return \$object;
EOF;

        $method = new MethodGenerator('getObject', [], MethodGenerator::FLAG_PUBLIC, $body, $doc);
        $method->setParameter(new ParameterGenerator('methods', 'array', []));
        $generator->addMethodFromGenerator($method);
    }

    /**
     * @param ClassGenerator $generator
     * @param State $state
     */
    public function addGetRepository(ClassGenerator $generator, State $state)
    {
        $class = $state->getRepositoryModel()->getClassName();
        $doc = "@return \\PHPUnit_Framework_MockObject_MockObject|" . $class;

        $class = $state->getRepositoryModel()->getName();
        $body
            = <<<EOF
return \$this->getMockBuilder('$class')
    ->disableOriginalConstructor()
    ->getMock();
EOF;

        $method = new MethodGenerator('getRepository', [], MethodGenerator::FLAG_PUBLIC, $body, $doc);
        $generator->addMethodFromGenerator($method);
    }

    /**
     * @param ClassGenerator $generator
     * @param State $state
     */
    public function addGetEntityManager(ClassGenerator $generator, State $state)
    {
        $doc = "@return \\PHPUnit_Framework_MockObject_MockObject|EntityManager";

        $body
            = <<<EOF
return \$this->getMockBuilder('Doctrine\ORM\EntityManager')
    ->disableOriginalConstructor()
    ->getMock();
EOF;

        $method = new MethodGenerator('getEntityManager', [], MethodGenerator::FLAG_PUBLIC, $body, $doc);
        $generator->addMethodFromGenerator($method);
    }

}