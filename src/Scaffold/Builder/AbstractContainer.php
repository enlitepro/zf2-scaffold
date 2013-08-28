<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace Scaffold\Builder;


use Scaffold\State;

abstract class AbstractContainer implements BuilderInterface
{

    /**
     * @var BuilderInterface[]
     */
    protected $builders = [];

    /**
     * @param State $state
     */
    public function prepare(State $state)
    {
        foreach ($this->builders as $builder) {
            $builder->prepare($state);
        }
    }

    /**
     * @param BuilderInterface $state
     */
    public function build(State $state)
    {
        foreach ($this->builders as $builder) {
            $builder->build($state);
        }
    }

    /**
     * @param BuilderInterface $builder
     */
    public function addBuilder(BuilderInterface $builder)
    {
        $this->builders[] = $builder;
    }

    /**
     * Return value of Builders
     *
     * @return \Scaffold\Builder\BuilderInterface[]
     */
    public function getBuilders()
    {
        return $this->builders;
    }

}