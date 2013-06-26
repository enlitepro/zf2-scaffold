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
use Zend\Code\Generator\MethodGenerator;
use Zend\Code\Generator\ParameterGenerator;

class FormBuilder extends AbstractBuilder
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
            ->addPart('Form')
            ->addPart($this->config->getName() . 'Form')
            ->getNamespace();

        $path = $this->buildPath()
            ->setModule($this->config->getModule())
            ->addPart('Form')
            ->addPart($this->config->getName() . 'Form')
            ->getPath();

        $model->setName($name);
        $model->setPath($path);
        $state->setFormModel($model);
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
        $model = $state->getFormModel();
        $generator = new ClassGenerator($model->getName());
        $generator->setImplementedInterfaces(['InputFilterProviderInterface']);

        $generator->addUse('Zend\\Form\\Form');
        $generator->addUse('Zend\InputFilter\InputFilterProviderInterface');
        $generator->setExtendedClass('Form');

        $this->buildConstruct($generator, $state);
        $this->buildGetInputFilter($generator, $state);

        $model->setGenerator($generator);
    }

    public function buildConstruct(ClassGenerator $generator, State $state)
    {
        $method = new MethodGenerator('__construct');
        $method->setParameter(new ParameterGenerator('name', 'string', lcfirst($state->getEntityModel()->getClassName())));
        $method->setParameter(new ParameterGenerator('options', 'array', []));

        $method->setBody(<<<EOF
parent::__construct(\$name, \$options);

\$this->add('submit');
EOF
);

        $generator->addMethodFromGenerator($method);
    }

    public function buildGetInputFilter(ClassGenerator $generator, State $state)
    {
        $method = new MethodGenerator('getInputFilterSpecification');

        $method->setBody('return array();');

        $generator->addMethodFromGenerator($method);
    }
}