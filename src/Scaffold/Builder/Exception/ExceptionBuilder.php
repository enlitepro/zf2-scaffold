<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace Scaffold\Builder\Exception;

use Scaffold\Builder\AbstractBuilder;
use Scaffold\State;
use Scaffold\Config;
use Scaffold\Model;
use Zend\Code\Generator\ClassGenerator;


class ExceptionBuilder extends AbstractBuilder
{

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $extends;

    /**
     * @var Model
     */
    protected $model;

    /**
     * @param Config $config
     * @param string $name
     * @param string $extends
     */
    public function __construct(Config $config, $name, $extends = '\RuntimeException')
    {
        $this->config = $config;
        $this->extends = $extends;
        $this->name = $name;
    }

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
            ->addPart($this->name)
            ->getNamespace();

        $path = $this->buildPath()
            ->setModule($this->config->getModule())
            ->addPart('Exception')
            ->addPart($this->name)
            ->getSourcePath();

        $model->setName($name);
        $model->setPath($path);
        $state->addModel($model, $this->name);
        $this->model = $model;
    }

    /**
     * Build generators
     *
     * @param State $state
     */
    public function build(State $state)
    {
        $generator = new ClassGenerator($this->model->getName());
        $generator->setExtendedClass($this->extends);

        $this->model->setGenerator($generator);
    }
}