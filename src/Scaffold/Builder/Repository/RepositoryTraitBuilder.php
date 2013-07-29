<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace Scaffold\Builder\Repository;


use Scaffold\Builder\AbstractBuilder;
use Scaffold\State;
use Scaffold\Code\Generator\TraitGenerator;
use Scaffold\Config;
use Scaffold\Model;
use Zend\Code\Generator\ClassGenerator;
use Zend\Code\Generator\DocBlock\Tag;

class RepositoryTraitBuilder extends AbstractBuilder
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
            ->addPart($this->config->getName() . 'RepositoryTrait')
            ->getNamespace();

        $path = $this->buildPath()
            ->setModule($this->config->getModule())
            ->addPart('Repository')
            ->addPart($this->config->getName() . 'RepositoryTrait')
            ->getPath();

        $model->setName($name);
        $model->setPath($path);
        $state->addModel($model, 'repository-trait');

        $this->model = $model;
    }


    /**
     * Build generators
     *
     * @param State|\Scaffold\State $state
     * @return \Scaffold\State|void
     */
    public function build(State $state)
    {
        $model = $this->model;

        $generator = new TraitGenerator($model->getName());
        $generator->addUse($state->getRepositoryModel()->getName());

        $property = lcfirst($state->getRepositoryModel()->getClassName());
        $class = $state->getRepositoryModel()->getClassName();
        $entity = $state->getEntityModel()->getName();

        $code
            = <<<EOF
if (null === \$this->$property) {
    \$this->$property = \$this->getEntityManager()->getRepository('$entity');
}
return \$this->$property;
EOF;

        $this->addProperty($generator, $property, $class);
        $this->addSetter($generator, $property, $class);

        $getter = $this->getGetter($property, $class);
        $getter->setBody($code);
        $generator->addMethodFromGenerator($getter);
//
        $model->setGenerator($generator);
    }
}