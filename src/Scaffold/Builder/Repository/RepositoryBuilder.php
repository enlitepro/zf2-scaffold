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
            ->getPath();

        $model->setName($name);
        $model->setPath($path);
        $state->setRepositoryModel($model);
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
        $model = $state->getRepositoryModel();
        $generator = new ClassGenerator($model->getName());
        $generator->addUse('Doctrine\ORM\EntityRepository');
        $generator->setExtendedClass('EntityRepository');

        $model->setGenerator($generator);
    }

}