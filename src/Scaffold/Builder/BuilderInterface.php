<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace Scaffold\Builder;


use Scaffold\AbstractState;

interface BuilderInterface
{

    /**
     * Prepare models
     *
     * @param AbstractState $state
     */
    public function prepare(AbstractState $state);

    /**
     * Build generators
     *
     * @param AbstractState $state
     */
    public function build(AbstractState $state);

}