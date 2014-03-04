<?php
/**
 * Seaf: Simple Easy Acceptable micro-framework.
 *
 * ヘルパコンテナを定義する
 *
 * 出来ること
 * =======================
 *
 * ヘルパをマップする
 *    $this->map('func',function($num1,$num2){ return $num1+$num2;});
 *    $this->invoke('func',1,2); // 3
 *
 * @author HAjime MATSUMOTO <mail@hazime.org>
 * @copyright Copyright (c) 2014, Seaf
 * @license   MIT, http://seaf.hazime.org
 */

namespace Seaf\Core\Environment;

use Seaf\Core\Base\Container;

/**
 * ヘルパクラス
 */
class HelperContainer extends Container
{
    /**
     * メソッドをマップする
     */
    public function map ( $name, $method )
    {
        if ($this->has($name)) {
            throw new AlreadyMaped($name);
        }
        parent::set($name,$method);
    }

    /**
     * メソッドをバインドする
     */
    public function bind ( $instance, array $methodList )
    {
        foreach ( $methodList as $to=>$from ) {
            $this->map($to,array($instance,$from));
        }
    }

    /**
     * メソッドを実行する
     */
    public function invoke ( $name )
    {
        $args = func_get_args();
        array_shift($args);

        return $this->invokeArgs($name,$args);
    }

    public function invokeArgs ( $name, $args = array() )
    {
        if ( !$this->has($name) ) {
            throw new UnMapedMethod($name);
        }
        return call_user_func_array( $this->get($name), $args );
    }

    public function __invoke ( $name )
    {
        $args = func_get_args();
        array_shift($args);

        return $this->invokeArgs($name,$args);
    }


}

/**
 * 既にマップされている
 */
class AlreadyMaped extends \Exception
{
    public function __construct( $name )
    {
        parent::__construct( $name."は既に登録されています。");
    }
}
/**
 * マップされていない
 */
class UnMapedMethod extends \Exception
{
    public function __construct( $name )
    {
        parent::__construct( $name."は登録されていません。");
    }
}





/* vim: set expandtab ts=4 sw=4 sts=4: et*/
