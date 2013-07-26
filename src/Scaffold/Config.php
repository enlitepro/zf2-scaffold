<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace Scaffold;


use Zend\Stdlib\AbstractOptions;

class Config extends AbstractOptions
{

    /**
     * @var string
     */
    protected $module;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var
     */
    protected $entityBuilder;

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

    /**
     * Set value of Module
     *
     * @param string $module
     */
    public function setModule($module)
    {
        $this->module = $module;
    }

    /**
     * Return value of Module
     *
     * @return string
     */
    public function getModule()
    {
        return $this->module;
    }

    /**
     * Set value of Name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = ucfirst($name);
    }

    /**
     * Return value of Name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

}