<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace Scaffold\Builder;


use Scaffold\State;

interface BuilderInterface
{

    /**
     * Prepare models
     *
     * @param State $state
     */
    public function prepare(State $state);

    /**
     * Build generators
     *
     * @param State $state
     */
    public function build(State $state);
}