<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace Scaffold\Builder;


use Scaffold\AbstractState;
use Scaffold\Code\Generator\ClassGenerator;
use Scaffold\Entity\Config;
use Scaffold\Entity\State;
use Scaffold\Model;
use Zend\Code\Generator\MethodGenerator;

class EntityTestBuilder extends AbstractBuilder
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * @var Model
     */
    protected $model;

    /**
     * @param AbstractState|State $state
     */
    public function prepare(AbstractState $state)
    {
        $model = new Model();
        $name = $this->buildNamespace()
            ->addPart($this->config->getModule() . 'Test')
            ->addPart('Entity')
            ->addPart($this->config->getName() . 'Test')
            ->getNamespace();

        $path = $this->buildPath()
            ->setType('test')
            ->setModule($this->config->getModule())
            ->addPart('Entity')
            ->addPart($this->config->getName())
            ->getPath();

        $model->setName($name);
        $model->setPath($path);
        $state->addModel($model);

        $this->model = $model;
    }


    /**
     * @param AbstractState|State $state
     * @return AbstractState|void
     */
    public function build(AbstractState $state)
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