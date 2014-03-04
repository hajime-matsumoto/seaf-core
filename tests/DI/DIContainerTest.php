<?php
namespace Seaf\Core\DI\Tests;

use Seaf\Core\DI;
use Seaf\Core\Factory\Factory;

class DIContainerTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateDIContainer()
    {
        $c = new DI\DIContainer( );
        $this->assertInstanceOf('Seaf\Core\DI\DIContainer',$c);
    }

    public function testConstrutWithFactory()
    {
        $c = new DI\DIContainer( new Factory() );
        $this->assertInstanceOf('Seaf\Core\DI\DIContainer',$c);

        $c->register( 'sample', 'Seaf\Core\DI\DIContainer' );

        $cc = new \Seaf\Core\Base\Container(array('a'=>'b'));

        $c->register('c',$cc);

        $this->assertInstanceOf('Seaf\Core\DI\DIContainer',$c->get('sample'));
        $c('sample')->set(array('a'=>'b'));

        $this->assertEquals('b',$c('sample')->get('a'));
        $this->assertEquals('b',$c('c')->get('a'));
    }

}
