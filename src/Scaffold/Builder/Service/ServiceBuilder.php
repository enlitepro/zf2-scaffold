<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace Scaffold\Builder\Service;


use Scaffold\Builder\AbstractBuilder;
use Scaffold\Builder\Service\ServiceFactoryBuilder;
use Scaffold\State;
use Scaffold\Code\Generator\ClassGenerator;
use Scaffold\Config;
use Scaffold\Model;
use Zend\Code\Generator\DocBlock\Tag;
use Zend\Code\Generator\DocBlockGenerator;
use Zend\Code\Generator\MethodGenerator;
use Zend\Code\Generator\ParameterGenerator;
use Zend\Code\Generator\PropertyGenerator;

class ServiceBuilder extends AbstractBuilder
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
        $model = new Model();
        $name = $this->buildNamespace()
            ->addPart($this->config->getModule())
            ->addPart('Service')
            ->addPart($this->config->getName() . 'Service')
            ->getNamespace();

        $path = $this->buildPath()
            ->setModule($this->config->getModule())
            ->addPart('Service')
            ->addPart($this->config->getName() . 'Service')
            ->getSourcePath();

        $model->setName($name);
        $model->setPath($path);
        $state->addModel($model, 'service');
    }

    /**
     * Build generators
     *
     * @param State|\Scaffold\State $state
     * @return \Scaffold\State|void
     */
    public function build(State $state)
    {
        $model = $state->getServiceModel();
        $generator = new ClassGenerator($model->getName());

        $generator->setImplementedInterfaces(['ServiceLocatorAwareInterface']);

        $generator->addUse('Doctrine\ORM\EntityManager');
        $generator->addUse($state->getEntityModel()->getName());
        $generator->addUse($state->getModel('NotFoundException')->getName());
        $generator->addUse($state->getModel('repository-trait')->getName());
        $generator->addUse('Zend\ServiceManager\ServiceLocatorAwareInterface');
        $generator->addUse('Zend\ServiceManager\ServiceLocatorAwareTrait');
        $generator->addUse('Zend\ServiceManager\ServiceLocatorInterface');

        $generator->addTrait('ServiceLocatorAwareTrait');
        $generator->addTrait($state->getModel('repository-trait')->getClassName());

        $this->addProperty($generator, 'entityManager', 'EntityManager');

        $this->buildConstructor($generator);
        $this->buildLoadById($generator, $state);
        $this->buildSearch($generator, $state);
        $this->buildSave($generator, $state);
        $this->buildDelete($generator, $state);
        $this->buildEntityManager($generator);
        $this->buildFactory($generator, $state);

        $model->setGenerator($generator);
    }

    /**
     * Build method factory
     *
     * @param ClassGenerator $generator
     * @param \Scaffold\State $state
     */
    public function buildFactory(ClassGenerator $generator, State $state)
    {
        $repository = ucfirst($state->getRepositoryModel()->getClassName());

        $docBlock = new DocBlockGenerator();
        $docBlock->setTag(new Tag(['name' => 'return', 'description' => $this->config->getName()]));

        $factory = new MethodGenerator();
        $factory->setDocBlock($docBlock);
        $factory->setName('factory');
        $factory->setBody('return $this->get' . $repository . '()->factory();');
        $generator->addMethodFromGenerator($factory);
    }

    protected function buildConstructor(ClassGenerator $generator)
    {
        $method = new MethodGenerator('__construct');
        $method->setParameter(new ParameterGenerator('serviceLocator', 'ServiceLocatorInterface'));
        $method->setDocBlock(new DocBlockGenerator());
        $method->getDocBlock()->setTag(
            new Tag(['name' => 'param', 'description' => 'ServiceLocatorInterface $serviceLocator'])
        );
        $method->setBody('$this->serviceLocator = $serviceLocator;');

        $generator->addMethodFromGenerator($method);
    }

    protected function buildLoadById(ClassGenerator $generator, State $state)
    {
        $repository = ucfirst($state->getRepositoryModel()->getClassName());
        $body
            = <<<EOF
\$model = \$this->get$repository()->find(\$id);
if (!\$model) {
    throw new NotFoundException('Cannot load model (' . \$id . ')');
}

return \$model;
EOF;

        $method = new MethodGenerator('loadById');
        $method->setParameter(new ParameterGenerator('id'));
        $method->setDocBlock(new DocBlockGenerator());
        $method->getDocBlock()->setTag(new Tag(['name' => 'param', 'description' => 'int $id']));
        $method->getDocBlock()->setTag(new Tag(['name' => 'throws', 'description' => 'NotFoundException']));
        $method->getDocBlock()->setTag(
            new Tag(['name' => 'return', 'description' => $state->getEntityModel()->getClassName()])
        );
        $method->setBody($body);

        $generator->addMethodFromGenerator($method);
    }

    protected function buildSearch(ClassGenerator $generator, State $state)
    {
        $repository = ucfirst($state->getRepositoryModel()->getClassName());
        $body = 'return $this->get' . $repository . '()->findBy($criteria);';

        $method = new MethodGenerator('search');
        $method->setParameter(new ParameterGenerator('criteria', 'array', []));
        $method->setDocBlock(new DocBlockGenerator());
        $method->getDocBlock()->setTag(new Tag(['name' => 'param', 'description' => 'array $criteria']));
        $method->getDocBlock()->setTag(
            new Tag(['name' => 'return', 'description' => $state->getEntityModel()->getClassName() . '[]'])
        );
        $method->setBody($body);

        $generator->addMethodFromGenerator($method);
    }

    protected function buildSave(ClassGenerator $generator, State $state)
    {
        $body = '$this->getEntityManager()->persist($model);';

        $method = new MethodGenerator('save');
        $method->setParameter(new ParameterGenerator('model', $state->getEntityModel()->getClassName()));
        $method->setDocBlock(new DocBlockGenerator());
        $method->getDocBlock()->setTag(
            new Tag(['name' => 'param', 'description' => $state->getEntityModel()->getClassName() . ' $model'])
        );
        $method->setBody($body);

        $generator->addMethodFromGenerator($method);
    }

    protected function buildDelete(ClassGenerator $generator, State $state)
    {
        $body = '$this->getEntityManager()->remove($model);';

        $method = new MethodGenerator('delete');
        $method->setParameter(new ParameterGenerator('model', $state->getEntityModel()->getClassName()));
        $method->setDocBlock(new DocBlockGenerator());
        $method->getDocBlock()->setTag(
            new Tag(['name' => 'param', 'description' => $state->getEntityModel()->getClassName() . ' $model'])
        );
        $method->setBody($body);

        $generator->addMethodFromGenerator($method);
    }

    protected function buildEntityManager(ClassGenerator $generator)
    {
        $setter = $this->getSetter('entityManager', 'EntityManager');
        $getter = $this->getLazyGetter(
            'entityManager',
            'EntityManager',
            '$this->getServiceLocator()->get(\'entity_manager\')'
        );

        $generator->addMethodFromGenerator($setter);
        $generator->addMethodFromGenerator($getter);
    }
}
