<?php
namespace Seaf\Component\Router\Tests;

use Seaf;

class RouterTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateContainer()
    {
        $router =  Seaf::router();
        $this->assertInstanceOf('Seaf\Component\Router', $router);
    }
    public function testMount()
    {
    }
    public function testMap()
    {
        Seaf::router( )->map('/', function( ) {
            return 'hello world';
        });
        Seaf::router( )->map('/@name', function( $name ) {
            return 'hello world '.$name;
        });

        $req = Seaf::request( )->newSimpleRequest('/',array(),'GET');
        $route = Seaf::router( )->route( $req );
        $this->assertEquals('hello world',$route->invoke());

        $req = Seaf::request( )->newSimpleRequest('/hajime',array(),'GET');
        $route = Seaf::router( )->route( $req );
        $this->assertEquals('hello world hajime',$route->invoke());


    }
    public function testRoute()
    {
    }
}
