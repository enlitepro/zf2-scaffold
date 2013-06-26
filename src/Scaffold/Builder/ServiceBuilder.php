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
     * @param AbstractState|State $state
     */
    public function prepare(AbstractState $state)
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
            ->getPath();

        $model->setName($name);
        $model->setPath($path);
        $state->setServiceModel($model);
        $state->addModel($model);
    }

    /**
     * Build generators
     *
     * @param AbstractState|State $state
     * @return \Scaffold\AbstractState|void
     */
    public function build(AbstractState $state)
    {
        $model = $state->getServiceModel();
        $generator = new ClassGenerator($model->getName());

        $generator->addUse('Zend\ServiceManager\ServiceManager');
        $generator->addUse('Doctrine\ORM\EntityManager');
        $generator->addUse($state->getRepositoryModel()->getName());
        $generator->addUse($state->getRuntimeException()->getName());


        $this->addProperty($generator, 'serviceManager', 'ServiceManager');
        $this->addProperty($generator, 'entityManager', 'EntityManager');
        $this->addProperty($generator, 'repository', $state->getRepositoryModel()->getClassName());

        $this->buildConstructor($generator);
        $this->buildLoadById($generator, $state);
        $this->buildSearch($generator, $state);
        $this->buildSave($generator, $state);
        $this->buildDelete($generator, $state);
        $this->buildEntityManager($generator);
        $this->buildRepository($generator, $state);

        $model->setGenerator($generator);
    }

    protected function buildConstructor(ClassGenerator $generator)
    {
        $method = new MethodGenerator('__construct');
        $method->setParameter(new ParameterGenerator('serviceManager', 'ServiceManager'));
        $method->setDocBlock(new DocBlockGenerator());
        $method->getDocBlock()->setTag(new Tag(['name' => 'param', 'description' => 'ServiceManager $serviceManager']));
        $method->setBody('$this->serviceManager = $serviceManager;');

        $generator->addMethodFromGenerator($method);
    }

    protected function buildLoadById(ClassGenerator $generator, State $state)
    {
        $body = <<<EOF
\$model = \$this->getRepository()->find(\$id);
if (!\$model) {
    throw new RuntimeException('Cannot load model (' . \$id . ')');
}

return \$model;
EOF;

        $method = new MethodGenerator('loadById');
        $method->setParameter(new ParameterGenerator('id'));
        $method->setDocBlock(new DocBlockGenerator());
        $method->getDocBlock()->setTag(new Tag(['name' => 'param', 'description' => 'int $id']));
        $method->getDocBlock()->setTag(new Tag(['name' => 'throws', 'description' => 'RuntimeException']));
        $method->getDocBlock()->setTag(new Tag(['name' => 'return', 'description' => $state->getEntityModel()->getClassName()]));
        $method->setBody($body);

        $generator->addMethodFromGenerator($method);
    }

    protected function buildSearch(ClassGenerator $generator, State $state)
    {
        $body = 'return $this->getRepository()->findBy($criteria);';

        $method = new MethodGenerator('search');
        $method->setParameter(new ParameterGenerator('criteria', 'array', []));
        $method->setDocBlock(new DocBlockGenerator());
        $method->getDocBlock()->setTag(new Tag(['name' => 'param', 'description' => 'array $criteria']));
        $method->getDocBlock()->setTag(new Tag(['name' => 'return', 'description' => $state->getEntityModel()->getClassName() . '[]']));
        $method->setBody($body);

        $generator->addMethodFromGenerator($method);
    }

    protected function buildSave(ClassGenerator $generator, State $state)
    {
        $body = '$this->getRepository()->persist($model);';

        $method = new MethodGenerator('save');
        $method->setParameter(new ParameterGenerator('model', $state->getEntityModel()->getClassName()));
        $method->setDocBlock(new DocBlockGenerator());
        $method->getDocBlock()->setTag(new Tag(['name' => 'param', 'description' => $state->getEntityModel()->getClassName() . ' $model']));
        $method->setBody($body);

        $generator->addMethodFromGenerator($method);
    }

    protected function buildDelete(ClassGenerator $generator, State $state)
    {
        $body = '$this->getEntityManager()->detach($model);';

        $method = new MethodGenerator('delete');
        $method->setParameter(new ParameterGenerator('model', $state->getEntityModel()->getClassName()));
        $method->setDocBlock(new DocBlockGenerator());
        $method->getDocBlock()->setTag(new Tag(['name' => 'param', 'description' => $state->getEntityModel()->getClassName() . ' $model']));
        $method->setBody($body);

        $generator->addMethodFromGenerator($method);
    }

    protected function buildEntityManager(ClassGenerator $generator)
    {
        $setter = $this->getSetter('entityManager', 'EntityManager');
        $getter = $this->getLazyGetter(
            'entityManager',
            'EntityManager',
            '$this->serviceManager->get(\'entity_manager\')'
        );

        $generator->addMethodFromGenerator($setter);
        $generator->addMethodFromGenerator($getter);
    }

    protected function buildRepository(ClassGenerator $generator, State $state)
    {
        $setter = $this->getSetter('repository', $state->getRepositoryModel()->getClassName());
        $getter = $this->getLazyGetter(
            'repository',
            $state->getRepositoryModel()->getClassName(),
            '$this->getEntityManager(\'' . $state->getEntityModel()->getName() . '\')'
        );

        $generator->addMethodFromGenerator($setter);
        $generator->addMethodFromGenerator($getter);
    }
}
