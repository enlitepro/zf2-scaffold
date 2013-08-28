<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace Scaffold\Builder\Repository;


use Scaffold\Builder\AbstractBuilder;
use Scaffold\State;
use Scaffold\Config;
use Scaffold\Model;
use Zend\Code\Generator\ClassGenerator;
use Zend\Code\Generator\DocBlockGenerator;
use Zend\Code\Generator\MethodGenerator;

class RepositoryBuilder extends AbstractBuilder
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
            ->addPart('Repository')
            ->addPart($this->config->getName() . 'Repository')
            ->getNamespace();

        $path = $this->buildPath()
            ->setModule($this->config->getModule())
            ->addPart('Repository')
            ->addPart($this->config->getName() . 'Repository')
            ->getSourcePath();

        $model->setName($name);
        $model->setPath($path);
        $state->addModel($model, 'repository');
    }

    /**
     * Build generators
     *
     * @param State|\Scaffold\State $state
     * @return \Scaffold\State|void
     */
    public function build(State $state)
    {
        $model = $state->getRepositoryModel();
        $generator = new ClassGenerator($model->getName());
        $generator->addUse('Doctrine\ORM\EntityRepository');
        $generator->addUse($state->getEntityModel()->getName());
        $generator->setExtendedClass('EntityRepository');

        $this->buildFactory($generator);

        $model->setGenerator($generator);
    }

    /**
     * Build method factory
     *
     * @param ClassGenerator $generator
     */
    public function buildFactory(ClassGenerator $generator)
    {
        $docBlock = new DocBlockGenerator('@return ' . $this->config->getName());
        $factory = new MethodGenerator();
        $factory->setDocBlock($docBlock);
        $factory->setName('factory');
        $factory->setBody('return new ' . $this->config->getName() . '();');
        $generator->addMethodFromGenerator($factory);
    }

}