<?php
namespace Seaf\Core\Environment\Tests;

use Seaf\Core\Environment\HelperContainer;

class HelperContainerTest extends \PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $c = new HelperContainer();
        $this->assertInstanceOf('Seaf\Core\Environment\HelperContainer',$c);
    }

    public function testMain()
    {
        $c = new HelperContainer();

        // map
        $c->map('test', function(){ return true; });

        $this->assertTrue($c->invoke('test'));
        $this->assertTrue($c->invokeArgs('test'));
        $this->assertTrue($c('test'));

        // bind
        $c->bind( $this, array('help'=>'_help','help2'=>'_help2') );

        $this->assertEquals( 'help', $c('help') );
        $this->assertEquals( 100, $c('help2', 30, 70) );
    }

    public function _help()
    {
        return 'help';
    }

    public function _help2( $num1, $num2 )
    {
        return $num1 + $num2;
    }
}
