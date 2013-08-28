<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace Scaffold\Builder\Form;


use Scaffold\Builder\AbstractBuilder;
use Scaffold\State;
use Scaffold\Config;
use Scaffold\Model;
use Zend\Code\Generator\ClassGenerator;
use Zend\Code\Generator\DocBlockGenerator;
use Zend\Code\Generator\MethodGenerator;
use Zend\Code\Generator\ParameterGenerator;

class FormFactoryBuilder extends AbstractBuilder
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
            ->addPart('Form')
            ->addPart($this->config->getName() . 'FormFactory')
            ->getNamespace();

        $path = $this->buildPath()
            ->setModule($this->config->getModule())
            ->addPart('Form')
            ->addPart($this->config->getName() . 'FormFactory')
            ->getSourcePath();

        $model->setName($name);
        $model->setPath($path);

        $config = array(
            'service_manager' => array(
                'factories' => array(
                    $model->getServiceName() => $name
                )
            )
        );
        $model->setServiceConfig($config);
        $state->addModel($model, 'form-factory');
    }

    /**
     * Build generators
     *
     * @param State $state
     * @return void
     */
    public function build(State $state)
    {
        $model = $state->getFormFactoryModel();
        $generator = new ClassGenerator($model->getName());
        $generator->setImplementedInterfaces(['FactoryInterface']);

        $generator->addUse('Doctrine\ORM\EntityManager');
        $generator->addUse('DoctrineModule\Stdlib\Hydrator\DoctrineObject');
        $generator->addUse('Zend\ServiceManager\FactoryInterface');
        $generator->addUse('Zend\ServiceManager\ServiceLocatorInterface');
        $generator->addUse('Zend\Form\Form');
        $generator->addUse('Zend\Form\Element');
        $generator->addUse('Zend\InputFilter\Factory');
        $generator->addUse('Zend\InputFilter\InputFilterInterface');

        $this->buildCreateService($generator, $state);
        $this->buildGetInputFilter($generator, $state);

        $model->setGenerator($generator);
    }

    public function buildCreateService(ClassGenerator $generator, State $state)
    {
        $method = new MethodGenerator('createService');
        $method->setDocBlock(new DocBlockGenerator());
        $method->getDocBlock()->setShortDescription('{@inheritdoc}');
        $method->setParameter(new ParameterGenerator('serviceLocator', 'ServiceLocatorInterface'));

        $name = lcfirst($state->getEntityModel()->getClassName());
        $entity = $state->getEntityModel()->getName();

        $method->setBody(
            <<<EOF
            \$form = new Form('$name');

\$submit = new Element\Submit('submit');
\$submit->setValue('Submit');
\$form->add(\$submit);

\$form->setInputFilter(\$this->getInputFilter());

/** @var EntityManager \$entityManager */
\$entityManager = \$serviceLocator->get('entity_manager');
\$form->setHydrator(new DoctrineObject(\$entityManager, '$entity'));

return \$form;
EOF
        );

        $generator->addMethodFromGenerator($method);
    }

    public function buildGetInputFilter(ClassGenerator $generator, State $state)
    {
        $method = new MethodGenerator('getInputFilter');
        $method->setDocBlock(new DocBlockGenerator());
        $method->getDocBlock()->setTag(['name' => 'return', 'description' => 'InputFilterInterface']);

        $method->setBody(<<<EOF
return (new Factory())->createInputFilter(
    array(

    )
);
EOF
);

        $generator->addMethodFromGenerator($method);
    }
}