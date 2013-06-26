<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace Scaffold;


use Symfony\Component\Console\Output\OutputInterface;

class Writer
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
        $path = realpath($this->config->getBasePath());
        if (!$path) {
            throw new \RuntimeException('Basepath not specify');
        }

        $path .= '/' . $model->getPath();
        $directory = dirname($path);

        if (!file_exists($directory)) {
            if (!mkdir($directory, 0777, true)) {
                throw new \RuntimeException('Can\'t create directory "' . $directory . '"');
            }
        }

        if (!is_writable($directory)) {
            throw new \RuntimeException('Can\'t write to directory "' . $directory . '"');
        }

        $data = $model->getGenerator()->generate();
        $data = '<?php' . PHP_EOL . PHP_EOL . $data;

        echo $data;

        if (file_put_contents($path, $data) === false) {
            throw new \RuntimeException('Can\'t write to file "' . $path . '"');
        }

        $output->writeln('<info>' . $model->getName() . '</info>: ' . $path);
    }

}