<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace Scaffold\Builder\Service;


use Scaffold\Builder\AbstractBuilder;
use Scaffold\State;
use Scaffold\Code\Generator\TraitGenerator;
use Scaffold\Config;
use Scaffold\Model;
use Zend\Code\Generator\ClassGenerator;
use Zend\Code\Generator\DocBlock\Tag;

class ServiceTraitBuilder extends AbstractBuilder
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
            ->addPart('Service')
            ->addPart($this->config->getName() . 'ServiceTrait')
            ->getNamespace();

        $path = $this->buildPath()
            ->setModule($this->config->getModule())
            ->addPart('Service')
            ->addPart($this->config->getName() . 'ServiceTrait')
            ->getSourcePath();

        $model->setName($name);
        $model->setPath($path);
        $state->addModel($model, 'service-trait');

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
        $generator->addUse($state->getServiceModel()->getName());
        $generator->addUse($state->getModel('RuntimeException')->getName());
        $generator->addUse('Zend\ServiceManager\ServiceLocatorAwareInterface');
        $generator->addUse('Zend\ServiceManager\ServiceLocatorInterface');

        $property = lcfirst($state->getServiceModel()->getClassName());
        $class = $state->getServiceModel()->getClassName();
        $alias = $state->getServiceModel()->getServiceName();

        $code
            = <<<EOF
if (null === \$this->$property) {
    if (\$this instanceof ServiceLocatorAwareInterface || method_exists(\$this, 'getServiceLocator')) {
        \$this->$property = \$this->getServiceLocator()->get('$alias');
    } else {
        if (property_exists(\$this, 'serviceLocator')
            && \$this->serviceLocator instanceof ServiceLocatorInterface
        ) {
            \$this->$property = \$this->serviceLocator->get('$alias');
        } else {
            throw new RuntimeException('Service locator not found');
        }
    }
}
return \$this->$property;
EOF;

        $this->addProperty($generator, $property, $class);
        $this->addSetter($generator, $property, $class);

        $getter = $this->getGetter($property, $class);
        $getter->setBody($code);
        $getter->getDocBlock()->setTag(
            new Tag(['name' => 'throws', 'description' => $state->getModel('RuntimeException')->getClassName()])
        );
        $generator->addMethodFromGenerator($getter);
//
        $model->setGenerator($generator);
    }
}