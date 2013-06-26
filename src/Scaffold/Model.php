<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace Scaffold;


use Zend\Code\Generator\GeneratorInterface;

class Model
{

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $path;

    /**
     * @var GeneratorInterface
     */
    protected $generator;

    /**
     * Set value of Generator
     *
     * @param \Zend\Code\Generator\GeneratorInterface $generator
     */
    public function setGenerator($generator)
    {
        $this->generator = $generator;
    }

    /**
     * Return value of Generator
     *
     * @return \Zend\Code\Generator\GeneratorInterface
     */
    public function getGenerator()
    {
        return $this->generator;
    }

    /**
     * Set value of Name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
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
     * Set value of Path
     *
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * Return value of Path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getClassName()
    {
        $parts = explode('\\', $this->getName());
        return array_pop($parts);
    }

    /**
     * @return string
     */
    public function getServiceName()
    {
        $parts = explode('\\', $this->getName());

        $module = array_shift($parts);
        $name = array_pop($parts);

        foreach (['Service', 'Form'] as $postfix) {
            if (substr($name, -strlen($postfix)) == $postfix) {
                if ($module == substr($name, 0, -strlen($postfix))) {
                    return $name;
                }
            }
        }

        return $module . $name;
    }

}