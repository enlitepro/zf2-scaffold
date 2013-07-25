<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace Scaffold\Builder\Module;


use Scaffold\AbstractState;
use Scaffold\Builder\AbstractBuilder;
use Scaffold\Code\Generator\RawGenerator;
use Scaffold\Entity\Config;
use Scaffold\Model;
use Scaffold\State;

class ModuleBuilder extends AbstractBuilder
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
            ->addPart('Module')
            ->getNamespace();

        $path = $this->buildPath()
            ->setModule($this->config->getModule())
            ->addPart('Module')
            ->setType('raw')
            ->getPath();

        $model->setName($name);
        $model->setPath($path);
        $state->addModel($model, 'module');
    }

    /**
     * Build generators
     *
     * @param AbstractState|State $state
     */
    public function build(AbstractState $state)
    {
        $model = $state->getModel('module');
        $data = file_get_contents(SCAFFOLD_ROOT . "/data/template/Module.php");
        $data = str_replace('__NAMESPACE_PLACEHOLDER__', ucfirst($this->config->getModule()), $data);
        $data = substr($data, 7);
        $model->setGenerator(new RawGenerator($data));
    }

}