<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace Scaffold;

use Traversable;
use Zend\Stdlib\AbstractOptions;
use Zend\Stdlib\Exception;

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
     * @var bool
     */
    protected $bare = false;

    /**
     * @var bool
     */
    protected $help = false;

    /**
     * @var bool
     */
    protected $quiet = false;

    /**
     * @var bool
     */
    protected $verbose = false;

    /**
     * @var string
     */
    protected $version;

    /**
     * @var bool
     */
    protected $ansi;

    /**
     * @param string $basePath
     */
    public function setBasePath($basePath)
    {
        $this->basePath = $basePath;
    }

    /**
     * @return string
     */
    public function getBasePath()
    {
        return $this->basePath;
    }

    /**
     * @param string $command
     */
    public function setCommand($command)
    {
        $this->command = $command;
    }

    /**
     * @return string
     */
    public function getCommand()
    {
        return $this->command;
    }

    /**
     * @param string $module
     */
    public function setModule($module)
    {
        $this->module = $module;
    }

    /**
     * @return string
     */
    public function getModule()
    {
        return $this->module;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = ucfirst($name);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param boolean $bare
     */
    public function setBare($bare)
    {
        $this->bare = $bare;
    }

    /**
     * @return boolean
     */
    public function getBare()
    {
        return $this->bare;
    }

    /**
     * @param boolean $quite
     */
    public function setQuiet($quite)
    {
        $this->quiet = $quite;
    }

    /**
     * @return boolean
     */
    public function getQuiet()
    {
        return $this->quiet;
    }

    /**
     * @param boolean $help
     */
    public function setHelp($help)
    {
        $this->help = $help;
    }

    /**
     * @return boolean
     */
    public function getHelp()
    {
        return $this->help;
    }

    /**
     * @param boolean $verbose
     */
    public function setVerbose($verbose)
    {
        $this->verbose = $verbose;
    }

    /**
     * @return boolean
     */
    public function getVerbose()
    {
        return $this->verbose;
    }

    /**
     * @param string $version
     */
    public function setVersion($version)
    {
        $this->version = $version;
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @param boolean $ansi
     */
    public function setAnsi($ansi)
    {
        $this->ansi = $ansi;
    }

    /**
     * @return boolean
     */
    public function getAnsi()
    {
        return $this->ansi;
    }

    /**
     * @param array|Traversable|AbstractOptions $options
     * @return AbstractOptions
     */
    public function setFromArray($options)
    {
        foreach(array_keys($options) as $key) {
            if (strpos($key, 'no-') === 0 || strpos($key, 'only-') === 0) {
                unset($options[$key]);
            }
        }

        return parent::setFromArray($options);
    }


}
