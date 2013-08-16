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
     * @var string
     */
    protected $type = 'src';

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
     * @param string $type
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }


    /**
     * @return string
     */
    public function getPath()
    {
        $path = implode("/", $this->parts);
        if ($this->type == 'src') {
            $path = 'module/' . ucfirst($this->module) . '/' . $this->type . '/' .
                ucfirst($this->module) . '/' . $path . '.php';
        } elseif ($this->type == 'raw') {
            $path = 'module/' . ucfirst($this->module) . '/' . $path . '.php';
        } elseif ($this->type == 'bin') {
            $path = 'module/' . ucfirst($this->module) . '/' . $path;
        } else {
            $path = 'module/' . ucfirst($this->module) . '/' . $this->type . '/' .
                ucfirst($this->module) . 'Test/' . $path . 'Test.php';
        }

        return $path;
    }
}