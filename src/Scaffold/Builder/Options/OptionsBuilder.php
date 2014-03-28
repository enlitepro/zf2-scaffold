<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace Scaffold\Builder\Options;


use Scaffold\Builder\AbstractBuilder;
use Scaffold\Code\Generator\ClassGenerator;
use Scaffold\Model;
use Scaffold\State;

class OptionsBuilder extends AbstractBuilder
{

    /**
     * @param \Scaffold\State $state
     */
    public function prepare(State $state)
    {
        $model = new Model();
        $name = $this->buildNamespace()
            ->addPart($this->config->getModule())
            ->addPart($this->config->getName() . 'Options')
            ->getNamespace();

        $path = $this->buildPath()
            ->setModule($this->config->getModule())
            ->addPart($this->config->getName() . 'Options')
            ->getSourcePath();

        $model->setName($name);
        $model->setPath($path);
        $state->addModel($model, 'options');
    }

    /**
     * @param  State|\Scaffold\State $state
     * @return State|void
     */
    public function build(State $state)
    {
        $model = $state->getModel('options');
        $generator = new ClassGenerator($model->getName());
        $generator->setExtendedClass('AbstractOptions');

        $model->setGenerator($generator);

        $generator->addUse('Zend\Stdlib\AbstractOptions');
    }
} 