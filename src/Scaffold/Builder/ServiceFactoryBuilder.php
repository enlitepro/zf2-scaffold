<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace Scaffold\Builder;


use Scaffold\AbstractState;
use Scaffold\Entity\Config;
use Scaffold\Entity\State;
use Scaffold\Model;
use Zend\Code\Generator\ClassGenerator;
use Zend\Code\Generator\DocBlock\Tag;
use Zend\Code\Generator\DocBlockGenerator;
use Zend\Code\Generator\MethodGenerator;
use Zend\Code\Generator\ParameterGenerator;

class ServiceFactoryBuilder extends AbstractBuilder
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
     * @param AbstractState|State $state
     */
    public function prepare(AbstractState $state)
    {
        $model = new Model();
        $name = $this->buildNamespace()
            ->addPart($this->config->getModule())
            ->addPart('Service')
            ->addPart($this->config->getName() . 'ServiceFactory')
            ->getNamespace();

        $path = $this->buildPath()
            ->setModule($this->config->getModule())
            ->addPart('Service')
            ->addPart($this->config->getName() . 'ServiceFactory')
            ->getPath();

        $model->setName($name);
        $model->setPath($path);
        $state->addModel($model);

        $config = array(
            'service_manager' => array(
                'factories' => array(
                    $state->getServiceModel()->getServiceName() => $name
                )
            )
        );

        $state->getModuleConfig()->merge($config);
        $this->model = $model;
    }

    /**
     * Build generators
     *
     * @param AbstractState|State $state
     * @return \Scaffold\AbstractState|void
     */
    public function build(AbstractState $state)
    {
        $model = $this->model;

        $generator = new ClassGenerator($model->getName());
        $generator->addUse('Zend\ServiceManager\FactoryInterface');
        $generator->addUse('Zend\ServiceManager\ServiceLocatorInterface');
        $generator->addUse('Zend\ServiceManager\ServiceManager');
        $generator->addUse($state->getServiceModel()->getName());
        $generator->setImplementedInterfaces(['FactoryInterface']);

        $method = new MethodGenerator('createService');
        $method->setParameter(new ParameterGenerator('serviceLocator', 'ServiceLocatorInterface'));
        $method->setBody('return new ' . $state->getServiceModel()->getClassName() . '($serviceLocator);');

        $doc = new DocBlockGenerator('Create service');
        $doc->setTag(new Tag(['name' => 'param', 'description' => 'ServiceLocatorInterface|ServiceManager $serviceLocator']));
        $doc->setTag(new Tag(['name' => 'return', 'description' => $state->getServiceModel()->getClassName()]));
        $method->setDocBlock($doc);

        $generator->addMethodFromGenerator($method);

        $model->setGenerator($generator);
    }

}