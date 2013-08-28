<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace ScaffoldTest\Builder\Form;


use Scaffold\Builder\Entity\EntityBuilder;
use Scaffold\Builder\Exception\ExceptionBuilder;
use Scaffold\Builder\Form\FormFactoryBuilder;
use Scaffold\Builder\SimpleContainer;
use Scaffold\Model;
use ScaffoldTest\Builder\AbstractBuilderTestCase;

class ExceptionBuilderTest extends AbstractBuilderTestCase
{

    public function testPrepare()
    {
        $builder = new ExceptionBuilder($this->getConfig(), 'NotFoundException');
        $state = $this->getStateMock();
        $state->expects($this->once())->method('addModel')->with(
            $this->callback(
                function (Model $model) {
                    $this->assertEquals('User\Exception\NotFoundException', $model->getName());
                    $this->assertEquals('module/User/src/User/Exception/NotFoundException.php', $model->getPath());

                    return true;
                }
            ),
            'NotFoundException'
        );

        $builder->prepare($state);

    }

    public function testBuild()
    {
        $state = $this->getState();
        $config = $this->getConfig();

        $prepare = new SimpleContainer();
        $prepare->addBuilder($builder = new ExceptionBuilder($this->getConfig(), 'NotFoundException'));
        $prepare->prepare($state);

        $builder->build($state);

        $fixture = file_get_contents(__DIR__ . "/fixture/exception.txt");
        $this->assertEquals($fixture, $state->getModel('NotFoundException')->getGenerator()->generate());
    }

}
 