<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace Scaffold;


use Scaffold\Exception\RuntimeException;
use Scaffold\Writer\ConfigWriter;

class State
{

    /**
     * @var Model
     */
    protected $repositoryModel;

    /**
     * @var Model
     */
    protected $serviceModel;

    /**
     * @var Model
     */
    protected $serviceTraitModel;

    /**
     * @var Model
     */
    protected $controllerModel;

    /**
     * @var Model
     */
    protected $formFactoryModel;

    /**
     * @var Model[]
     */
    protected $models = [];

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
     * Return value of EntityModel
     *
     * @return Model
     */
    public function getEntityModel()
    {
        return $this->getModel('entity');
    }

    /**
     * Return value of RepositoryModel
     *
     * @return Model
     */
    public function getRepositoryModel()
    {
        return $this->getModel('repository');
    }

    /**
     * Return value of ServiceModel
     *
     * @return Model
     */
    public function getServiceModel()
    {
        return $this->getModel('service');
    }

    /**
     * Return value of ControllerModel
     *
     * @return Model
     */
    public function getControllerModel()
    {
        return $this->getModel('controller');
    }

    /**
     * Return value of FormModel
     *
     * @return Model
     */
    public function getFormFactoryModel()
    {
        return $this->getModel('form-factory');
    }

    /**
     * Return value of ServiceTraitModel
     *
     * @return Model
     */
    public function getServiceTraitModel()
    {
        return $this->getModel('service-trait');
    }

    /**
     * Return all models
     *
     * @return Model[]
     */
    public function getModels()
    {
        return $this->models;
    }

    /**
     * @param Model $model
     * @param string $alias
     */
    public function addModel(Model $model, $alias = null)
    {
        if ($alias) {
            $this->models[$alias] = $model;
        } else {
            $this->models[] = $model;
        }
    }

    /**
     * @param string $alias
     * @return Model
     * @throws RuntimeException
     */
    public function getModel($alias)
    {
        if (isset($this->models[$alias])) {
            return $this->models[$alias];
        }

        throw new RuntimeException('Model not found (' . $alias . ')');
    }

}