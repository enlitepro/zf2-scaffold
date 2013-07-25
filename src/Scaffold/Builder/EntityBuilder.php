<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace Scaffold\Builder;


use Scaffold\AbstractState;
use Scaffold\Entity\Config;
use Scaffold\State;
use Scaffold\Model;
use Zend\Code\Generator\ClassGenerator;
use Zend\Code\Generator\DocBlock\Tag;
use Zend\Code\Generator\DocBlockGenerator;
use Zend\Code\Generator\PropertyGenerator;

class EntityBuilder extends AbstractBuilder
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * @param AbstractState|\Scaffold\State $state
     */
    public function prepare(AbstractState $state)
    {
        $model = new Model();
        $name = $this->buildNamespace()
            ->addPart($this->config->getModule())
            ->addPart('Entity')
            ->addPart($this->config->getName())
            ->getNamespace();

        $path = $this->buildPath()
            ->setModule($this->config->getModule())
            ->addPart('Entity')
            ->addPart($this->config->getName())
            ->getPath();

        $model->setName($name);
        $model->setPath($path);
        $state->setEntityModel($model);
        $state->addModel($model);
    }


    /**
     * @param AbstractState|\Scaffold\State $state
     * @return AbstractState|void
     */
    public function build(AbstractState $state)
    {
        $model = $state->getEntityModel();
        $generator = new ClassGenerator($model->getName());
        $model->setGenerator($generator);

        $generator->addUse('Doctrine\ORM\Mapping', 'ORM');
        $generator->setDocBlock($this->getClassDocBlock($state));
        $generator->addPropertyFromGenerator($this->getIdProperty());
        $this->addGetter($generator, 'id', 'int');
        $this->addSetter($generator, 'id', 'int');
    }

    protected function getClassDocBlock(State $state)
    {
        $repository = $state->getRepositoryModel()->getName();

        $doc = new DocBlockGenerator();
        $doc->setTag(new Tag(['name' => 'ORM\Entity(repositoryClass="' . $repository . '")']));
        $doc->setTag(new Tag(['name' => 'ORM\Table(name="' . strtolower($this->config->getName()) . '")']));

        return $doc;
    }

    /**
     * @return PropertyGenerator
     */
    protected function getIdProperty()
    {
        $id = new PropertyGenerator('id', null, PropertyGenerator::FLAG_PROTECTED);
        $id->setDocBlock(new DocBlockGenerator());
        $id->getDocBlock()->setTag(new Tag(['name' => 'var', 'description' => 'int']));
        $id->getDocBlock()->setTag(new Tag(['name' => 'ORM\Id']));
        $id->getDocBlock()->setTag(new Tag(['name' => 'ORM\GeneratedValue']));
        $id->getDocBlock()->setTag(new Tag(['name' => 'ORM\Column(type="integer")']));

        return $id;
    }

}