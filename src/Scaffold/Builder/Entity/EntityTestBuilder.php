<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace Scaffold\Builder\Entity;


use Scaffold\Builder\AbstractBuilder;
use Scaffold\State;
use Scaffold\Code\Generator\ClassGenerator;
use Scaffold\Model;
use Zend\Code\Generator\MethodGenerator;

class EntityTestBuilder extends AbstractBuilder
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * @param State|\Scaffold\State $state
     */
    public function prepare(State $state)
    {
        $model = new Model();
        $name = $this->buildNamespace()
            ->addPart($this->config->getModule() . 'Test')
            ->addPart('Entity')
            ->addPart($this->config->getName() . 'Test')
            ->getNamespace();

        $path = $this->buildPath()
            ->setModule($this->config->getModule())
            ->addPart('Entity')
            ->addPart($this->config->getName())
            ->getTestPath();

        $model->setName($name);
        $model->setPath($path);
        $state->addModel($model, 'entity-test');

        $this->model = $model;
    }


    /**
     * @param State|\Scaffold\State $state
     * @return State|void
     */
    public function build(State $state)
    {
        $model = $this->model;
        $generator = new ClassGenerator($model->getName());
        $model->setGenerator($generator);

        $generator->setExtendedClass('\PHPUnit_Framework_TestCase');
        $generator->addUse($state->getEntityModel()->getName());
        $this->addTestIdMethod($generator, $state);
    }

    public function addTestIdMethod(ClassGenerator $generator, State $state)
    {
        $method = new MethodGenerator('testGetSetId');
        $class = $state->getEntityModel()->getClassName();

        $code
            = <<<EOF
\$object = new $class();
\$object->setId(123);
\$this->assertEquals(123, \$object->getId());
EOF;
        $method->setBody($code);
        $generator->addMethodFromGenerator($method);
    }

}