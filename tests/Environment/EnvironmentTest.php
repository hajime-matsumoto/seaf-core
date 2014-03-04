<?php
namespace Seaf\Core\Environment\Tests;

use Seaf\Core\Environment\Environment;

class EnvironmentTest extends \PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $env = new Environment();
        $this->assertInstanceOf('Seaf\Core\Environment\Environment',$env);
    }

    public function testSome()
    {
        $env = new Environment();

        $env->map('test', function($n1,$n2){
            return $n1 * $n2;
        });

        $this->assertEquals( 8, $env->test( 2, 4) );

    }

    public function testComponentSystem( )
    {
        $env = new Environment();

        // systemコンポーネントを動的に呼び出す
        $this->assertTrue( is_array( $env->system( )->iniGetAll( ) ) );
    }
}
