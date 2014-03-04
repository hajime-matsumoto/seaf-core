<?php
/**
 * Seaf: Simple Easy Acceptable micro-framework.
 *
 * ファクトリを定義する
 *
 * 出来ること
 * =======================
 *
 * イニシャライザを登録する
 * クラスを生成する
 *
 * @author HAjime MATSUMOTO <mail@hazime.org>
 * @copyright Copyright (c) 2014, Seaf
 * @license   MIT, http://seaf.hazime.org
 */

namespace Seaf\Core\Factory;

use Seaf\Core\Base\Container;

/**
 * ファクトリクラス
 */
class Factory extends Container
{
    /**
     * イニシャライザを登録する
     */
    public function set( $name, $initializer, $callback = false)
    {
        parent::set( $name, array(
            'init'=>$initializer,
            'cb'=>$callback
        ));
    }

    /**
     * エイリアス
     */
    public function register( $name, $initializer, $callback = false)
    {
        return $this->set($name,$initializer,$callback);
    }


    /**
     * インスタンスを生成する
     */
    public function create( $name )
    {
        if ( !$this->has($name) ) {
            throw new InitializerNotExists($name);
        }

        // 可変長引数の処理
        $args = func_get_args();
        array_shift($args);

        $info = $this->get($name);

        $instance = $this->newInstance($info['init'],$args);

        if (is_callable($info['cb'])) {
            return call_user_func($info['cb'], $instance, $args);
        }
        return $instance;
    }

    private function newInstance($init,$args)
    {
        if ( is_callable($init) ) {
            return call_user_func_array($init, $args);
        }

        if ( is_string($init) ) {
            $rc = new \ReflectionClass($init);
            return $rc->newInstanceArgs($args);
        }
    }
}

/**
 * イニシャライザが登録されていない例外
 */
class InitializerNotExists extends \Exception
{
    public function __construct( $name )
    {
        parent::__construct( $name."は登録されていないイニシャライザです");
    }
}



/* vim: set expandtab ts=4 sw=4 sts=4: et*/
