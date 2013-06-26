<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace Scaffold\Entity;


use Scaffold\AbstractConfig;

class Config extends AbstractConfig
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