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
     * Return all models
     *
     * @return Model[]
     */
    public function getModels()
    {
        return array(
            $this->getEntityModel(),
            $this->getRepositoryModel(),
            $this->getServiceModel()
        );
    }

}