<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace Scaffold\Builder;


use Scaffold\AbstractState;

class Container implements BuilderInterface
{

    /**
     * @var BuilderInterface[]
     */
    protected $builders = [];

    /**
     * @param AbstractState $state
     */
    public function prepare(AbstractState $state)
    {
        foreach ($this->builders as $builder) {
            $builder->prepare($state);
        }
    }

    /**
     * @param BuilderInterface $state
     */
    public function build(AbstractState $state)
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

}