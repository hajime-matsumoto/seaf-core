<?php
namespace Seaf\Core\Base\Tests;

use Seaf\Core\Base;

class ContainerTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateContainer()
    {
        $container = new Base\Container( );
        $container_with_default = new Base\Container( array('a'=>'b') );

        $this->assertEquals('b',$container_with_default->get('a'));
    }

    public function testIO()
    {
        $c = new Base\Container( );

        $c->set('a','b');

        $this->assertEquals('b',$c->get('a'));
        $this->assertEquals('c',$c->get('c','c'));

        try {
            $c->set('a','b');
        }catch( \Exception $e ) {
            $this->assertTrue(true);
        }

        $c->del('a');
        $this->assertFalse( $c->has('a') );
        $c->set('a','b');
        $this->assertTrue( $c->has('a') );
    }

    public function testSetArray()
    {
        $c = new Base\Container( );

        $c->set(array('a'=>'b','c'=>'d'));

        $this->assertEquals('b',$c->get('a'));
        $this->assertEquals('d',$c->get('c'));

        foreach($c as $k=>$v)
        {
            $array[$k] = $v;
        }

        $this->assertEquals('b',$array['a']);
        $this->assertEquals('d',$array['c']);
    }
}
