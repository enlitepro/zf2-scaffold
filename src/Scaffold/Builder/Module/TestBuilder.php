<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace Scaffold\Builder\Module;


use Scaffold\Builder\AbstractBuilder;
use Scaffold\Code\Generator\BinGenerator;
use Scaffold\Config;
use Scaffold\Model;
use Scaffold\State;

class TestBuilder extends AbstractBuilder
{

    /**
     * @var Config
     */
    protected $config;

    /**
     * @var string
     */
    protected $name;

    /**
     * @param Config $config
     * @param string $name
     */
    public function __construct(Config $config, $name)
    {
        $this->config = $config;
        $this->name = $name;
    }

    /**
     * Prepare models
     *
     * @param State|State $state
     */
    public function prepare(State $state)
    {
        $model = new Model();
        $path = $this->buildPath()
            ->setModule($this->config->getModule())
            ->addPart('test')
            ->addPart(basename($this->name))
            ->getRawPath('');

        $model->setPath($path);
        $state->addModel($model, $this->name);
    }

    /**
     * Build generators
     *
     * @param State|State $state
     */
    public function build(State $state)
    {
        $model = $state->getModel($this->name);
        $data = file_get_contents(SCAFFOLD_ROOT . "/data/template/" . $this->name);
        $data = str_replace('__NAMESPACE_PLACEHOLDER__', ucfirst($this->config->getModule()), $data);
        $model->setGenerator(new BinGenerator($data));
    }

}