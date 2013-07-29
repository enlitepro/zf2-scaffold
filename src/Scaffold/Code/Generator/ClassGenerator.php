<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace Scaffold\Code\Generator;

use Zend\Code\Generator\ClassGenerator as ZendClassGenerator;

class ClassGenerator extends ZendClassGenerator
{

    /**
     * @var array
     */
    protected $traits = array();

    /**
     * Set value of Traits
     *
     * @param array $traits
     */
    public function setTraits(array $traits)
    {
        $this->traits = $traits;
    }

    /**
     * Return value of Traits
     *
     * @return array
     */
    public function getTraits()
    {
        return $this->traits;
    }

    /**
     * @param string $trait
     */
    public function addTrait($trait)
    {
        $this->traits[] = $trait;
    }

    /**
     * @return string
     */
    public function generate()
    {
        if (!$this->isSourceDirty()) {
            $output = $this->getSourceContent();
            if (!empty($output)) {
                return $output;
            }
        }

        $output = '';

        if (null !== ($namespace = $this->getNamespaceName())) {
            $output .= 'namespace ' . $namespace . ';' . self::LINE_FEED . self::LINE_FEED;
        }

        $uses = $this->getUses();
        if (!empty($uses)) {
            foreach ($uses as $use) {
                $output .= 'use ' . $use . ';' . self::LINE_FEED;
            }
            $output .= self::LINE_FEED;
        }

        if (null !== ($docBlock = $this->getDocBlock())) {
            $docBlock->setIndentation('');
            $output .= $docBlock->generate();
        }

        if ($this->isAbstract()) {
            $output .= 'abstract ';
        }

        $output .= 'class ' . $this->getName();

        if (!empty($this->extendedClass)) {
            $output .= ' extends ' . $this->extendedClass;
        }

        $implemented = $this->getImplementedInterfaces();
        if (!empty($implemented)) {
            $output .= ' implements ' . implode(', ', $implemented);
        }

        $output .= self::LINE_FEED . '{' . self::LINE_FEED . self::LINE_FEED;

        $traits = $this->getTraits();
        if (!empty($traits)) {
            $output .= $this->getIndentation();
            $output .= 'use ' . implode(',' . self::LINE_FEED . $this->getIndentation() .
                    $this->getIndentation(), $traits) . ';';
            $output .= self::LINE_FEED;
            $output .= self::LINE_FEED;
        }

        $properties = $this->getProperties();
        if (!empty($properties)) {
            foreach ($properties as $property) {
                $output .= $property->generate() . self::LINE_FEED . self::LINE_FEED;
            }
        }

        $methods = $this->getMethods();
        if (!empty($methods)) {
            foreach ($methods as $method) {
                $output .= $method->generate() . self::LINE_FEED;
            }
        }

        $output .= self::LINE_FEED . '}' . self::LINE_FEED;

        return $output;
    }

}