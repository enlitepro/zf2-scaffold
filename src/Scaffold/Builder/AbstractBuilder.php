<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace Scaffold\Builder;


use Scaffold\Config;
use Scaffold\NamespaceBuilder;
use Scaffold\PathBuilder;
use Scaffold\State;
use Zend\Code\Generator\ClassGenerator;
use Zend\Code\Generator\DocBlock\Tag;
use Zend\Code\Generator\DocBlockGenerator;
use Zend\Code\Generator\MethodGenerator;
use Zend\Code\Generator\ParameterGenerator;
use Zend\Code\Generator\PropertyGenerator;

abstract class AbstractBuilder implements BuilderInterface
{

    /**
     * @var string
     */
    protected $name;

    /**
     * @var Config
     */
    protected $config;

    /**
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->setConfig($config);
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

    /**
     * @return NamespaceBuilder
     */
    protected function buildNamespace()
    {
        return new NamespaceBuilder();
    }

    /**
     * @return PathBuilder
     */
    protected function buildPath()
    {
        return new PathBuilder();
    }

    /**
     * Set value of Config
     *
     * @param \Scaffold\Config $config
     */
    public function setConfig($config)
    {
        $this->config = $config;
    }

    /**
     * @param ClassGenerator $generator
     * @param string $name
     * @param string $type
     */
    protected function addProperty(ClassGenerator $generator, $name, $type)
    {
        $generator->addPropertyFromGenerator($this->getProperty($name, $type));
    }

    /**
     * @param string $name
     * @param string $type
     * @return PropertyGenerator
     */
    protected function getProperty($name, $type)
    {
        $property = new PropertyGenerator($name, null, PropertyGenerator::FLAG_PROTECTED);
        $property->setDocBlock(new DocBlockGenerator());
        $property->getDocBlock()->setTag(new Tag(['name' => 'var', 'description' => $type]));
        return $property;
    }

    /**
     * @param ClassGenerator $generator
     * @param string $name
     * @param string $type
     */
    public function addGetter(ClassGenerator $generator, $name, $type)
    {
        $generator->addMethodFromGenerator($this->getGetter($name, $type));
    }

    /**
     * @param string $name
     * @param string $type
     * @return \Zend\Code\Generator\MethodGenerator
     */
    protected function getGetter($name, $type)
    {
        $method = new MethodGenerator('get' . ucfirst($name));
        $method->setDocBlock(new DocBlockGenerator());
        $method->getDocBlock()->setTag(new Tag(['name' => 'return', 'description' => $type]));
        $method->setBody('return $this->' . $name . ';');

        return $method;
    }

    /**
     * @param ClassGenerator $generator
     * @param string $name
     * @param string $type
     * @param string $lazy
     */
    public function addLazyGetter(ClassGenerator $generator, $name, $type, $lazy)
    {
        $generator->addMethodFromGenerator($this->getLazyGetter($name, $type, $lazy));
    }

    /**
     * @param string $name
     * @param string $type
     * @param string $lazy
     * @return \Zend\Code\Generator\MethodGenerator
     */
    protected function getLazyGetter($name, $type, $lazy)
    {
        $method = $this->getGetter($name, $type);
        $body
            = <<<EOF
if (null === \$this->$name) {
    \$this->$name = $lazy;
}

return \$this->$name;
EOF;

        $method->setBody($body);

        return $method;
    }

    /**
     * @param ClassGenerator $generator
     * @param string $name
     * @param string $type
     */
    public function addSetter(ClassGenerator $generator, $name, $type)
    {
        $generator->addMethodFromGenerator($this->getSetter($name, $type));
    }

    /**
     * @param string $name
     * @param string $type
     * @return \Zend\Code\Generator\MethodGenerator
     */
    protected function getSetter($name, $type)
    {
        $method = new MethodGenerator('set' . ucfirst($name));
        $method->setParameter(new ParameterGenerator($name, $type));

        $method->setDocBlock(new DocBlockGenerator());
        $method->getDocBlock()->setTag(new Tag(['name' => 'param', 'description' => $type . ' $' . $name]));
        $method->setBody('$this->' . $name . ' = $' . $name . ';');

        return $method;
    }

}