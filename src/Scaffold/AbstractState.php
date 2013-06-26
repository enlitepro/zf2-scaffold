<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace Scaffold;


abstract class AbstractState
{

    /**
     * Return all models
     *
     * @return Model[]
     */
    abstract public function getModels();

}