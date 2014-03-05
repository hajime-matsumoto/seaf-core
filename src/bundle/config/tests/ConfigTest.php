<?php
namespace Seaf\Config\Tests;

use Seaf;

class ConfigTest extends \PHPUnit_Framework_TestCase
{
    public function setup ()
    {
        define('APP_ROOT',dirname(__FILE__));
    }

    public function testLoadConfig()
    {
        $config = Seaf::config();

        $file = dirname(__FILE__).'/setting.yaml';

        $config->load($file);

        // Envが切り替わっているか
        $this->assertEquals('Seaf Project(development)', $config->get('name'));

        $config->setEnvName( 'production' );
        $this->assertEquals('Seaf Project(production)', $config->get('name'));

        // section[ENV]が存在しないときにdefaultセクションに切り替わっているか
        $this->assertEquals('Hajime MATSUMOTO', $config->get('author'));

        // 配列が帰るか
        $this->assertTrue( is_array($config->get('path')) );

        // .区切りが使えるか
        $this->assertTrue( is_array($config->get('view')) );
        $this->assertEquals( 'twig', $config->get('view.engine') );
        $this->assertEquals( 'ok', $config->get('long.action.trigger.on') );

        // セットできるか
        $config->set('runtime.path.info','abc');
        $this->assertEquals( 'abc', $config->get('runtime.path.info') );

        // 上書きしてないか
        $this->assertEquals( '/bin/php', $config->get('runtime.php.path') );

        // リセットできるか
        $config->set('runtime.path', array('info'=>'a'));
        $this->assertEquals( 'a', $config->get('runtime.path.info') );
    }
}
