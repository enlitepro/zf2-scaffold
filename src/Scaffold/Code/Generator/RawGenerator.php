<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace Scaffold\Code\Generator;


use Zend\Code\Generator\GeneratorInterface;

class RawGenerator implements GeneratorInterface
{

    /**
     * @var string
     */
    protected $code = '';

    /**
     * @param string $code
     */
    public function __construct($code)
    {
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function generate()
    {
        return $this->code;
    }

}