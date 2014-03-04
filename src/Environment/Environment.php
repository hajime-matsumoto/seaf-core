<?php
/**
 * Seaf: Simple Easy Acceptable micro-framework.
 *
 * 環境クラスを定義する
 *
 * 環境とは
 * ========================
 *
 * インスタンスをアタッチしたり
 * ヘルパーをアタッチしたり
 * 変数を保存したり
 * ワークスペースとなるベースクラス
 *
 * @author HAjime MATSUMOTO <mail@hazime.org>
 * @copyright Copyright (c) 2014, Seaf
 * @license   MIT, http://seaf.hazime.org
 */

namespace Seaf\Core\Environment;

use Seaf\Core\DI\DIContainer;
use Seaf\Core\Factory\Factory;

/**
 * 環境クラス
 */
class Environment
{
    /**
     * DIコンテナ
     * @var object
     */
    private $di;

    /**
     * ヘルパコンテナ
     */
    private $helper;


    /**
     * 環境オブジェクトを初期化
     */
    public function init()
    {
        $this->di = new DIContainer(new Factory());
        $this->helper = new HelperContainer();

        /**
         * ヘルパメソッドを登録する
         */
        $this->helper->bind( $this->helper, array(
            'bind'    => 'bind',
            'map'     => 'map',
            'isMaped' => 'has'
        ));

        /**
         * DI操作メソッドを登録する
         */
        $this->helper->bind( $this->di, array(
            'register'     => 'register',
            'isRegistered' => 'has',
            'di'           => 'get'
        ));
    }

    public function __construct( )
    {
        $this->init();
    }


    /**
     * ヘルパメソッドを有効にする
     */
    public function __call ( $name, $params )
    {
        if( $this->helper->has( $name ) ) {
            return $this->helper->invokeArgs( $name, $params );
        }

        throw new UndefinedCall( $name );
    }


}

class UndefinedCall extends \Exception
{
    public function __construct( $key )
    {
        parent::__construct( sprintf( "%sは解決できない呼び出しです", $key ) );
    }
}

/* vim: set expandtab ts=4 sw=4 sts=4: et*/
