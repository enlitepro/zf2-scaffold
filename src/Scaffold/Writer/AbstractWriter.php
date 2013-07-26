<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace Scaffold\Writer;


use Scaffold\Config;
use Symfony\Component\Console\Output\OutputInterface;

abstract class AbstractWriter
{

    /**
     * @var \Scaffold\Config
     */
    protected $config;

    /**
     * @param \Scaffold\Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * @param $pathToWrite
     * @param string $data
     * @param OutputInterface $output
     * @throws \RuntimeException
     */
    protected function writeData($pathToWrite, $data, OutputInterface $output)
    {
        $path = realpath($this->config->getBasePath());

        if (!$path) {
            throw new \RuntimeException('Base path not specify');
        }

        $path .= '/' . $pathToWrite;
        $directory = dirname($path);

        if (!file_exists($directory)) {
            if (!mkdir($directory, 0777, true)) {
                throw new \RuntimeException('Can\'t create directory "' . $directory . '"');
            }
        }

        if (!is_writable($directory)) {
            throw new \RuntimeException('Can\'t write to directory "' . $directory . '"');
        }

        if (file_put_contents($path, $data) === false) {
            throw new \RuntimeException('Can\'t write to file "' . $path . '"');
        }

        $output->writeln('<info>write</info>: ' . $path);
    }
}