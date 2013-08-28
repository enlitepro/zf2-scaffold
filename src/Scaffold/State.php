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
    protected $entityModel;

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
     * Set value of EntityModel
     *
     * @param Model $entityModel
     * @deprecated
     */
    public function setEntityModel($entityModel)
    {
        $this->entityModel = $entityModel;
    }

    /**
     * Return value of EntityModel
     *
     * @return Model
     */
    public function getEntityModel()
    {
        return $this->entityModel;
    }

    /**
     * Set value of RepositoryModel
     *
     * @param Model $repositoryModel
     * @deprecated
     */
    public function setRepositoryModel($repositoryModel)
    {
        $this->repositoryModel = $repositoryModel;
    }

    /**
     * Return value of RepositoryModel
     *
     * @return Model
     */
    public function getRepositoryModel()
    {
        return $this->repositoryModel;
    }

    /**
     * Set value of ServiceModel
     *
     * @param Model $serviceModel
     * @deprecated
     */
    public function setServiceModel($serviceModel)
    {
        $this->serviceModel = $serviceModel;
    }

    /**
     * Return value of ServiceModel
     *
     * @return Model
     */
    public function getServiceModel()
    {
        return $this->serviceModel;
    }

    /**
     * Set value of ControllerModel
     *
     * @param Model $controllerModel
     * @deprecated
     */
    public function setControllerModel($controllerModel)
    {
        $this->controllerModel = $controllerModel;
    }

    /**
     * Return value of ControllerModel
     *
     * @return Model
     */
    public function getControllerModel()
    {
        return $this->controllerModel;
    }

    /**
     * Set value of FormModel
     *
     * @param Model $formModel
     * @deprecated
     */
    public function setFormFactoryModel($formModel)
    {
        $this->formFactoryModel = $formModel;
    }

    /**
     * Return value of FormModel
     *
     * @return Model
     */
    public function getFormFactoryModel()
    {
        return $this->formFactoryModel;
    }

    /**
     * Set value of ServiceTraitModel
     *
     * @param Model $serviceTraitModel
     * @deprecated
     */
    public function setServiceTraitModel($serviceTraitModel)
    {
        $this->serviceTraitModel = $serviceTraitModel;
    }

    /**
     * Return value of ServiceTraitModel
     *
     * @return Model
     */
    public function getServiceTraitModel()
    {
        return $this->serviceTraitModel;
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