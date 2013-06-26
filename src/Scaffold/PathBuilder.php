<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace Scaffold;


class PathBuilder
{
    /**
     * @var string
     */
    protected $parts = [];

    /**
     * @var string
     */
    protected $module;

    /**
     * @param string $part
     * @return $this
     */
    public function addPart($part)
    {
        $this->parts[] = $part;

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
    public function getPath()
    {
        $path = implode("/", $this->parts);
        $path = 'module/' . ucfirst($this->module) . '/src/' . ucfirst($this->module) . '/' . $path . '.php';

        return $path;
    }
}