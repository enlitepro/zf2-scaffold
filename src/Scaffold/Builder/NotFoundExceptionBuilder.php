<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace Scaffold\Builder;


use Scaffold\State;
use Scaffold\Config;
use Scaffold\Model;
use Zend\Code\Generator\ClassGenerator;

class NotFoundExceptionBuilder extends AbstractBuilder
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * Prepare models
     *
     * @param State|\Scaffold\State $state
     */
    public function prepare(State $state)
    {
        $model = new Model();
        $name = $this->buildNamespace()
            ->addPart($this->config->getModule())
            ->addPart('Exception')
            ->addPart('NotFoundException')
            ->getNamespace();

        $path = $this->buildPath()
            ->setModule($this->config->getModule())
            ->addPart('Exception')
            ->addPart('NotFoundException')
            ->getPath();

        $model->setName($name);
        $model->setPath($path);
        $state->setNotFoundException($model);
        $state->addModel($model);
    }

    /**
     * Build generators
     *
     * @param State|\Scaffold\State $state
     * @return \Scaffold\State|void
     */
    public function build(State $state)
    {
        $model = $state->getNotFoundException();
        $generator = new ClassGenerator($model->getName());
        $generator->setExtendedClass('RuntimeException');

        $model->setGenerator($generator);
    }
}