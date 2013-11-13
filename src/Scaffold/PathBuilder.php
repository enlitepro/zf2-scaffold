<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace Scaffold;

class PathBuilder
{

    /**
     * @var Config
     */
    protected $config;

    /**
     * @var string
     */
    protected $parts = [];

    /**
     * @var string
     */
    protected $module;

    /**
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * @param string $part
     * @return $this
     */
    public function addPart($part)
    {
        $this->parts[] = str_replace('\\', '/', $part);

        return $this;
    }

    /**
     * @param string $module
     * @return $this
     */
    public function setModule($module)
    {
        $this->module = $module;

        return $this;
    }

    /**
     * @return string
     */
    public function getSourcePath()
    {
        return $this->getModuleBase() . 'src/' .
        ucfirst($this->module) . '/' . implode("/", $this->parts) . '.php';
    }

    /**
     * @param  string $extension
     * @return string
     */
    public function getRawPath($extension = 'php')
    {
        $path = $this->getModuleBase() . implode("/", $this->parts);
        if ($extension) {
            return $path . '.' . $extension;
        }

        return $path;
    }

    /**
     * @return string
     */
    public function getTestPath()
    {
        return $this->getModuleBase() . 'test/' . ucfirst($this->module) .
        'Test/' . implode("/", $this->parts) . 'Test.php';
    }

    /**
     * @return string
     */
    public function getModuleBase()
    {
        $module = ucfirst($this->module);

        if ($this->config->getBare()) {
            return '';
        }

        return 'module/' . $module . '/';
    }
}
