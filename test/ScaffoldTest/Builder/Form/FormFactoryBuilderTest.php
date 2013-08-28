<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace ScaffoldTest\Builder\Form;


use Scaffold\Builder\Entity\EntityBuilder;
use Scaffold\Builder\Form\FormFactoryBuilder;
use Scaffold\Builder\SimpleContainer;
use Scaffold\Model;
use ScaffoldTest\Builder\AbstractBuilderTestCase;

class FormFactoryBuilderTest extends AbstractBuilderTestCase
{

    public function testPrepare()
    {
        $builder = new FormFactoryBuilder($this->getConfig());
        $state = $this->getStateMock();
        $state->expects($this->once())->method('addModel')->with(
            $this->callback(
                function (Model $model) {
                    $this->assertEquals('User\Form\GroupFormFactory', $model->getName());
                    $this->assertEquals('module/User/src/User/Form/GroupFormFactory.php', $model->getPath());
                    $this->assertSame(
                        array(
                             'service_manager' => array(
                                 'factories' => array(
                                     'UserGroupForm' => 'User\Form\GroupFormFactory'
                                 )
                             )
                        ),
                        $model->getServiceConfig()
                    );

                    return true;
                }
            ),
            'form-factory'
        );

        $builder->prepare($state);

    }

    public function testBuild()
    {
        $state = $this->getState();
        $config = $this->getConfig();

        $prepare = new SimpleContainer();
        $prepare->addBuilder(new EntityBuilder($config));
        $prepare->addBuilder($builder = new FormFactoryBuilder($config));
        $prepare->prepare($state);

        $builder->build($state);

        $fixture = file_get_contents(__DIR__ . "/fixture/form.txt");
        $this->assertEquals($fixture, $state->getModel('form-factory')->getGenerator()->generate());
    }

}
 