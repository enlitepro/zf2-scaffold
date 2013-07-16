<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace Scaffold\Code\Generator;

class TraitGenerator extends ClassGenerator
{
    /**
     * @return string
     */
    public function generate()
    {
        $code = parent::generate();
        $code = str_replace('class ' . $this->getName(), 'trait ' . $this->getName(), $code);

        return $code;
    }


}