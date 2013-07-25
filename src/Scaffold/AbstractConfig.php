<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace Scaffold;

use Zend\Stdlib\AbstractOptions;

class AbstractConfig extends AbstractOptions
{

    /**
     * @var string
     */
    protected $basePath = '';

    /**
     * @var string
     */
    protected $command = '';

    /**
     * Set value of BasePath
     *
     * @param string $basePath
     */
    public function setBasePath($basePath)
    {
        $this->basePath = $basePath;
    }

    /**
     * Return value of BasePath
     *
     * @return string
     */
    public function getBasePath()
    {
        return $this->basePath;
    }

    /**
     * Set value of Command
     *
     * @param string $command
     */
    public function setCommand($command)
    {
        $this->command = $command;
    }

    /**
     * Return value of Command
     *
     * @return string
     */
    public function getCommand()
    {
        return $this->command;
    }


}