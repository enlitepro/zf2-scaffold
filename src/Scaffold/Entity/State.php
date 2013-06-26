<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace Scaffold\Entity;


use Scaffold\AbstractState;
use Scaffold\Model;

class State extends AbstractState
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
    protected $controllerModel;

    /**
     * @var Model
     */
    protected $formModel;

    /**
     * @var Model
     */
    protected $runtimeException;

    /**
     * @var Model[]
     */
    protected $models = [];

    /**
     * Set value of EntityModel
     *
     * @param \Scaffold\Model $entityModel
     */
    public function setEntityModel($entityModel)
    {
        $this->entityModel = $entityModel;
    }

    /**
     * Return value of EntityModel
     *
     * @return \Scaffold\Model
     */
    public function getEntityModel()
    {
        return $this->entityModel;
    }

    /**
     * Set value of RepositoryModel
     *
     * @param \Scaffold\Model $repositoryModel
     */
    public function setRepositoryModel($repositoryModel)
    {
        $this->repositoryModel = $repositoryModel;
    }

    /**
     * Return value of RepositoryModel
     *
     * @return \Scaffold\Model
     */
    public function getRepositoryModel()
    {
        return $this->repositoryModel;
    }

    /**
     * Set value of ServiceModel
     *
     * @param \Scaffold\Model $serviceModel
     */
    public function setServiceModel($serviceModel)
    {
        $this->serviceModel = $serviceModel;
    }

    /**
     * Return value of ServiceModel
     *
     * @return \Scaffold\Model
     */
    public function getServiceModel()
    {
        return $this->serviceModel;
    }

    /**
     * Set value of ControllerModel
     *
     * @param \Scaffold\Model $controllerModel
     */
    public function setControllerModel($controllerModel)
    {
        $this->controllerModel = $controllerModel;
    }

    /**
     * Return value of ControllerModel
     *
     * @return \Scaffold\Model
     */
    public function getControllerModel()
    {
        return $this->controllerModel;
    }

    /**
     * Set value of RuntimeException
     *
     * @param \Scaffold\Model $runtimeException
     */
    public function setRuntimeException($runtimeException)
    {
        $this->runtimeException = $runtimeException;
    }

    /**
     * Return value of RuntimeException
     *
     * @return \Scaffold\Model
     */
    public function getRuntimeException()
    {
        return $this->runtimeException;
    }

    /**
     * Set value of FormModel
     *
     * @param \Scaffold\Model $formModel
     */
    public function setFormModel($formModel)
    {
        $this->formModel = $formModel;
    }

    /**
     * Return value of FormModel
     *
     * @return \Scaffold\Model
     */
    public function getFormModel()
    {
        return $this->formModel;
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
     */
    public function addModel(Model $model)
    {
        $this->models[] = $model;
    }

}