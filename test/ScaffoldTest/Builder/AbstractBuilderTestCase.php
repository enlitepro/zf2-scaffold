<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace ScaffoldTest\Builder;


use Scaffold\Config;
use Scaffold\Model;
use Scaffold\State;
use Scaffold\Writer\ConfigWriter;

abstract class AbstractBuilderTestCase extends \PHPUnit_Framework_TestCase
{

    public function getConfig()
    {
        $config = new Config();
        $config->setModule('User');
        $config->setName('Group');

        return $config;
    }

    /**
     * @return \Scaffold\State|\PHPUnit_Framework_MockObject_MockObject
     */
    public function getStateMock()
    {
        return $this->getMockBuilder('Scaffold\State')
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @return \Scaffold\State
     */
    public function getState()
    {
        return new State(new ConfigWriter($this->getConfig()));
    }

    /**
     * @param $name
     * @return Model
     */
    public function getModel($name)
    {
        $model = new Model();
        $model->setName($name);

        return $model;
    }

} 