<?php
namespace Seaf\Core\Factory\Tests;

use Seaf\Core\Factory;

class FactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateFactory()
    {
        $f = new Factory\Factory( );
        $this->assertInstanceOf('Seaf\Core\Factory\Factory', $f);
    }

    public function testStringInit()
    {
        $f = new Factory\Factory( );

        $f->set('str', 'Seaf\Core\Factory\Factory');
        $this->assertInstanceOf('Seaf\Core\Factory\Factory', $f->create('str'));
        $this->assertInstanceOf('Seaf\Core\Factory\Factory', $f->create('str'));

        $f->del('str');

        // 差し替え
        $f->set('str', 'Seaf\Core\Factory\Factory',function($ins,$opt){
            return new \Seaf\Core\Base\Container();
        });
        $this->assertInstanceOf('Seaf\Core\Base\Container', $f->create('str'));

        $f->del('str');

        // オプションを使う
        $f->set('str', 'Seaf\Core\Factory\Factory',function($ins,$opt){
            if ($opt[0] === true) {
                return new \Seaf\Core\Base\Container();
            }
        });
        $this->assertInstanceOf('Seaf\Core\Base\Container', $f->create('str',true));

        $f->del('str');

        // 引数付の起動
        $f->set('str', 'Seaf\Core\Base\Container',function($ins,$opt){
            return $ins;
        });
        $this->assertInstanceOf('Seaf\Core\Base\Container', $f->create('str',array('a'=>'b')));
        $this->assertEquals('b', $f->create('str',array('a'=>'b'))->get('a'));
    }

    public function testCallbackInit()
    {
        $f = new Factory\Factory( );

        $f->set('str',function(){
            return new Factory\Factory();
        });

        $this->assertInstanceOf('Seaf\Core\Factory\Factory', $f->create('str'));
    }

}
