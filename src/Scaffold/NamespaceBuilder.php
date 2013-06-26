<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace Scaffold;


class NamespaceBuilder
{

    /**
     * @var string
     */
    protected $parts = [];

    /**
     * @param string $part
     * @return $this
     */
    public function addPart($part)
    {
        $this->parts[] = ucfirst($part);

        return $this;
    }

    /**
     * @return string
     */
    public function getNamespace()
    {
        return implode("\\", $this->parts);
    }

}