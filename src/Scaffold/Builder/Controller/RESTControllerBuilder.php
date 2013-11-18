<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace Scaffold\Builder\Controller;

use Scaffold\Builder\AbstractBuilder;
use Scaffold\Code\Generator\ClassGenerator;
use Scaffold\Config;
use Scaffold\Model;
use Scaffold\State;
use Zend\Code\Generator\DocBlockGenerator;
use Zend\Code\Generator\MethodGenerator;

class RESTControllerBuilder extends AbstractBuilder
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * Prepare models
     *
     * @param State|State $state
     */
    public function prepare(State $state)
    {
        $filename = $this->config->getName() . 'Controller';

        $model = new Model();
        $name = $this->buildNamespace()
            ->addPart($this->config->getModule())
            ->addPart('Controller')
            ->addPart($filename)
            ->getNamespace();

        $path = $this->buildPath()
            ->setModule($this->config->getModule())
            ->addPart('Controller')
            ->addPart($filename)
            ->getSourcePath();

        $model->setName($name);
        $model->setPath($path);

        $config = array(
            'controllers' => array(
                'invokables' => array(
                    $model->getServiceName() => $model->getName()
                )
            )
        );
        $model->setServiceConfig($config);
        $state->addModel($model, 'controller');
    }

    /**
     * Build generators
     *
     * @param  State|State          $state
     * @return \Scaffold\State|void
     */
    public function build(State $state)
    {
        $model = $state->getControllerModel();
        $generator = new ClassGenerator($model->getName());
        $generator->setExtendedClass('AbstractRestfulController');
        $generator->addUse('Zend\Mvc\Controller\AbstractRestfulController');
        $generator->addUse('Doctrine\ORM\EntityManager');
        $generator->addUse('Zend\Form\Form');
        $generator->addUse($state->getServiceTraitModel()->getName());

        $generator->addTrait($state->getServiceTraitModel()->getClassName());

        $this->addProperty($generator, 'entityManager', 'EntityManager');

        $this->buildGetEntityManager($generator, $state);

        $model->setGenerator($generator);
    }

    public function buildGetEntityManager(ClassGenerator $generator, State $state)
    {
        $this->addSetter($generator, 'entityManager', 'EntityManager');

        $body = '$this->getServiceLocator()->get(\'entity_manager\')';
        $this->addLazyGetter($generator, 'entityManager', 'EntityManager', $body);
    }
}
