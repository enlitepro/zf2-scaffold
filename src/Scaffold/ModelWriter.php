<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace Scaffold;


use Symfony\Component\Console\Output\OutputInterface;

class ModelWriter extends AbstractWriter
{

    /**
     * @var AbstractConfig
     */
    protected $config;

    /**
     * @param AbstractConfig $config
     */
    public function __construct(AbstractConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param AbstractState $state
     * @param OutputInterface $output
     */
    public function write(AbstractState $state, OutputInterface $output)
    {
        foreach ($state->getModels() as $model) {
            $this->writeModel($model, $output);
        }
    }

    /**
     * @param Model $model
     * @param OutputInterface $output
     * @throws \RuntimeException
     */
    public function writeModel(Model $model, OutputInterface $output)
    {
        $path = $this->config->getBasePath();
        $path .= '/' . $model->getPath();

        $data = $model->getGenerator()->generate();
        $data = '<?php' . PHP_EOL . PHP_EOL . $data;

        $this->writeData($path, $data, $output);
    }

}