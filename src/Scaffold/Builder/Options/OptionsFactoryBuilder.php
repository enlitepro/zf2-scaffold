<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace Scaffold\Builder\Options;


use Scaffold\Builder\AbstractBuilder;
use Scaffold\Code\Generator\ClassGenerator;
use Scaffold\Model;
use Scaffold\State;
use Zend\Code\Generator\DocBlock\Tag;
use Zend\Code\Generator\DocBlockGenerator;
use Zend\Code\Generator\MethodGenerator;
use Zend\Code\Generator\ParameterGenerator;

class OptionsFactoryBuilder extends AbstractBuilder
{

    /**
     * @param \Scaffold\State $state
     */
    public function prepare(State $state)
    {
        $model = new Model();
        $name = $this->buildNamespace()
            ->addPart($this->config->getModule())
            ->addPart($this->config->getName() . 'OptionsFactory')
            ->getNamespace();

        $path = $this->buildPath()
            ->setModule($this->config->getModule())
            ->addPart($this->config->getName() . 'OptionsFactory')
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

        $state->addModel($model, 'options-factory');
    }

    /**
     * @param  State|\Scaffold\State $state
     * @return State|void
     */
    public function build(State $state)
    {
        $model = $state->getModel('options-factory');
        $generator = new ClassGenerator($model->getName());
        $generator->setImplementedInterfaces(['FactoryInterface']);
        $model->setGenerator($generator);

        $generator->addUse('Zend\ServiceManager\FactoryInterface');
        $generator->addUse('Zend\ServiceManager\ServiceLocatorInterface');
        $options = $state->getModel('options');

        $key = $options->getServiceName();
        $key = substr($key, 0, -7);

        $body
            = <<<EOF
\$config = \$serviceLocator->get('Config');
return new {$options->getClassName()}(
    isset(\$config['$key'])
        ? \$config['$key']
        : []
);
EOF;


        $method = new MethodGenerator('createService');
        $method->setParameter(new ParameterGenerator('serviceLocator', 'ServiceLocatorInterface'));
        $method->setBody($body);

        $doc = new DocBlockGenerator('');
        $doc->setTag(new Tag(['name' => 'inhertidoc']));
        $method->setDocBlock($doc);

        $generator->addMethodFromGenerator($method);

    }
}