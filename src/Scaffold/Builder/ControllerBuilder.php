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
use Zend\Code\Generator\DocBlockGenerator;
use Zend\Code\Generator\MethodGenerator;

class ControllerBuilder extends AbstractBuilder
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
            ->getPath();

        $model->setName($name);
        $model->setPath($path);
        $state->setControllerModel($model);
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
        $model = $state->getControllerModel();
        $generator = new ClassGenerator($model->getName());
        $generator->setExtendedClass('AbstractActionController');
        $generator->addUse('Zend\Mvc\Controller\AbstractActionController');
        $generator->addUse('Doctrine\ORM\EntityManager');
        $generator->addUse($state->getServiceModel()->getName());
        $generator->addUse($state->getFormModel()->getName());

        $this->addProperty(
            $generator,
            lcfirst($state->getServiceModel()->getClassName()),
            $state->getServiceModel()->getClassName()
        );
        $this->addProperty($generator, 'entityManager', 'EntityManager');

        $this->buildIndexAction($generator, $state);
        $this->buildListAction($generator, $state);
        $this->buildEditAction($generator, $state);

        $this->buildGetService($generator, $state);
        $this->buildGetEntityManager($generator, $state);

        $model->setGenerator($generator);
    }

    public function buildGetService(ClassGenerator $generator, State $state)
    {
        $property = lcfirst($state->getServiceModel()->getClassName());
        $this->addSetter($generator, $property, $state->getServiceModel()->getClassName());

        $body = '$this->getServiceLocator()->get("' . $state->getServiceModel()->getServiceName() . '")';
        $this->addLazyGetter($generator, $property, $state->getServiceModel()->getClassName(), $body);
    }

    public function buildGetEntityManager(ClassGenerator $generator, State $state)
    {
        $this->addSetter($generator, 'entityManager', 'EntityManager');

        $body = '$this->serviceManager->get(\'entity_manager\')';
        $this->addLazyGetter($generator, 'entityManager', 'EntityManager', $body);
    }

    public function buildIndexAction(ClassGenerator $generator, State $state)
    {
        $service = 'get' . $state->getServiceModel()->getClassName();
        $name = lcfirst($state->getEntityModel()->getClassName());

        $method = new MethodGenerator('indexAction');
        $method->setDocBlock(new DocBlockGenerator('Show one entity'));

        $method->setBody(<<<EOF
\$id = \$this->params()->fromRoute('id');
\$$name = \$this->$service()->loadById(\$id);

return array(
    '$name' => \$$name
);
EOF
);
        $generator->addMethodFromGenerator($method);
    }

    public function buildListAction(ClassGenerator $generator, State $state)
    {
        $service = 'get' . $state->getServiceModel()->getClassName();
        $name = lcfirst($state->getEntityModel()->getClassName());

        $method = new MethodGenerator('listAction');
        $method->setDocBlock(new DocBlockGenerator('Show list of entities'));

        $method->setBody(<<<EOF
\${$name}s = \$this->$service()->search();

return array(
    '{$name}s' => \${$name}s
);
EOF
        );
        $generator->addMethodFromGenerator($method);
    }

    public function buildEditAction(ClassGenerator $generator, State $state)
    {
        $service = 'get' . $state->getServiceModel()->getClassName();
        $name = lcfirst($state->getEntityModel()->getClassName());

        $method = new MethodGenerator('editAction');
        $method->setDocBlock(new DocBlockGenerator('Show one entity'));

        $method->setBody(<<<EOF
\$id = \$this->params()->fromRoute('id');
\$$name = \$this->$service()->loadById(\$id);
/** @var {$state->getFormModel()->getClassName()} \$form */
\$form = \$this->getServiceLocator()->get('{$state->getFormModel()->getServiceName()}');
\$form->bind(\$$name);

if (\$this->getRequest()->isPost()) {
    \$form->setData(\$this->params()->fromPost());
    if (\$form->isValid()) {
        \$this->$service()->save(\$$name);
        \$this->getEntityManager()->flush();

        \$this->flashMessenger()->addSuccessMessage('Saved');
        \$this->redirect()->toRoute('home');
    }
}

return array(
    '\$form' => \$form
);
EOF
        );
        $generator->addMethodFromGenerator($method);
    }

}