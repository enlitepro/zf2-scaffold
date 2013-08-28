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

class ControllerBuilder extends AbstractBuilder
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
     * @param State|State $state
     * @return \Scaffold\State|void
     */
    public function build(State $state)
    {
        $model = $state->getControllerModel();
        $generator = new ClassGenerator($model->getName());
        $generator->setExtendedClass('AbstractActionController');
        $generator->addUse('Zend\Mvc\Controller\AbstractActionController');
        $generator->addUse('Doctrine\ORM\EntityManager');
        $generator->addUse('Zend\Form\Form');
        $generator->addUse($state->getServiceTraitModel()->getName());

        $generator->addTrait($state->getServiceTraitModel()->getClassName());

        $this->addProperty($generator, 'entityManager', 'EntityManager');

        $this->buildIndexAction($generator, $state);
        $this->buildListAction($generator, $state);
        $this->buildEditAction($generator, $state);

        $this->buildGetEntityManager($generator, $state);

        $model->setGenerator($generator);
    }

    public function buildGetEntityManager(ClassGenerator $generator, State $state)
    {
        $this->addSetter($generator, 'entityManager', 'EntityManager');

        $body = '$this->getServiceLocator()->get(\'entity_manager\')';
        $this->addLazyGetter($generator, 'entityManager', 'EntityManager', $body);
    }

    public function buildIndexAction(ClassGenerator $generator, State $state)
    {
        $service = 'get' . $state->getServiceModel()->getClassName();
        $name = lcfirst($state->getEntityModel()->getClassName());

        $method = new MethodGenerator('indexAction');
        $method->setDocBlock(new DocBlockGenerator('Show one entity'));

        $method->setBody(
            <<<EOF
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

        $method->setBody(
            <<<EOF
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

        $method->setBody(
            <<<EOF
            \$id = \$this->params()->fromRoute('id');
\$$name = \$this->$service()->loadById(\$id);
/** @var Form \$form */
\$form = \$this->getServiceLocator()->get('{$state->getFormFactoryModel()->getServiceName()}');
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
    'form' => \$form
);
EOF
        );
        $generator->addMethodFromGenerator($method);
    }

}