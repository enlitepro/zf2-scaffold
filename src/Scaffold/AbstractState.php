<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace Scaffold;


use Scaffold\Writer\ConfigWriter;

abstract class AbstractState
{

    /**
     * @var ConfigWriter
     */
    protected $moduleConfig;

    /**
     * @param ConfigWriter $moduleConfig
     */
    public function __construct(ConfigWriter $moduleConfig)
    {
        $this->moduleConfig = $moduleConfig;
    }

    /**
     * Return value of ModuleConfig
     *
     * @return \Scaffold\Writer\ConfigWriter
     */
    public function getModuleConfig()
    {
        return $this->moduleConfig;
    }


    /**
     * Return all models
     *
     * @return Model[]
     */
    abstract public function getModels();

}