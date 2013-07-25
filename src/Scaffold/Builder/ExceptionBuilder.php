<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace Scaffold\Builder;


use Scaffold\AbstractState;
use Scaffold\Entity\Config;
use Scaffold\State;
use Scaffold\Model;
use Zend\Code\Generator\ClassGenerator;

class ExceptionBuilder extends AbstractBuilder
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * Prepare models
     *
     * @param AbstractState|\Scaffold\State $state
     */
    public function prepare(AbstractState $state)
    {
        $model = new Model();
        $name = $this->buildNamespace()
            ->addPart($this->config->getModule())
            ->addPart('Exception')
            ->addPart('RuntimeException')
            ->getNamespace();

        $path = $this->buildPath()
            ->setModule($this->config->getModule())
            ->addPart('Exception')
            ->addPart('RuntimeException')
            ->getPath();

        $model->setName($name);
        $model->setPath($path);
        $state->setRuntimeException($model);
        $state->addModel($model);
    }

    /**
     * Build generators
     *
     * @param AbstractState|\Scaffold\State $state
     * @return \Scaffold\AbstractState|void
     */
    public function build(AbstractState $state)
    {
        $model = $state->getRuntimeException();
        $generator = new ClassGenerator($model->getName());
        $generator->setExtendedClass('\RuntimeException');

        $model->setGenerator($generator);
    }
}