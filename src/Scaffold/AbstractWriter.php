<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace Scaffold;


use Symfony\Component\Console\Output\OutputInterface;

abstract class AbstractWriter
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
     * @param $pathToWrite
     * @param string $data
     * @param OutputInterface $output
     * @throws \RuntimeException
     */
    public function writeData($pathToWrite, $data, OutputInterface $output)
    {
        $path = realpath($pathToWrite);

        if (!$path) {
            throw new \RuntimeException('Basepath not specify');
        }

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