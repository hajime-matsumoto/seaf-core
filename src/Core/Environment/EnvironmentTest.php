<?php
namespace Seaf\Core\Environment;

use Seaf;
/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.1 on 2014-03-06 at 08:38:28.
 */
class EnvironmentTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Environment
     */
    protected $env;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->env = new Environment( );
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers Seaf\Core\Environment\Environment::init
     * @todo   Implement testInit().
     */
    public function testInit()
    {
        $this->env->init();
    }

    /**
     * @covers Seaf\Core\Environment\Environment::setEnvMode
     * @todo   Implement testSetEnvMode().
     */
    public function testSetEnvMode()
    {
        $this->env->setEnvMode('dev');
        $this->assertEquals('dev', $this->env->getEnvMode());
    }

    /**
     * @covers Seaf\Core\Environment\Environment::getEnvMode
     * @todo   Implement testGetEnvMode().
     */
    public function testGetEnvMode()
    {
        $this->assertEquals(Seaf::ENV_DEVELOPMENT, $this->env->getEnvMode());
    }

    /**
     * @covers Seaf\Core\Environment\Environment::addComponentNamespace
     * @todo   Implement testAddComponentNamespace().
     */
    public function testAddComponentNamespace()
    {

        $ns_list = $this->env->addComponentNamespace('Test');
        $this->assertEquals('Test', $ns_list[0]);
    }

    /**
     * @covers Seaf\Core\Environment\Environment::di
     * @todo   Implement testDi().
     */
    public function testDi()
    {
        $this->assertEquals(
            $this->env,
            $this->env->di('environment')
        );
    }

    /**
     * @covers Seaf\Core\Environment\Environment::call
     * @todo   Implement testCall().
     */
    public function testMap()
    {
        $this->env->map('test',function(){
            return true;
        });

        $this->assertTrue($this->env->isMaped('test'));
        $this->env->bind( $this->env, array(
            'maped' => 'isMaped'
        ));

        $this->assertTrue(
            $this->env->maped('isMaped')
        );
    }

}
