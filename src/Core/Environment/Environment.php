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

use Seaf;
use Seaf\Core\DI\DIContainer;
use Seaf\Core\Factory\Factory;
use Seaf\Core\Base\Container;

/**
 * 環境クラス
 * ========================
 *
 * メソッド拡張
 * ----------------
 *  Seaf::map(<name>,<function>)
 *  Seaf::bind(<object>,[<name>,<method>])
 *  Seaf::isMaped(<name) bool
 *
 * オブジェクトの登録
 * ---------------
 *  Seaf::register(<name>,<initializer>,<callback>)
 *  Seaf::di(<name>) インスタンスの取得
 *
 * オートロードの仕組み
 * --------------
 * __NAMESPACE__.'\\Component'のネームスペースで
 * 作られたクラスは
 *  Seaf::di(<name>)
 * で呼び出せる
 */
class Environment extends Container
{
    /**
     * モード
     */
    private $mode_name = null;

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
     * コンポーネントネームスペース
     */
    private $component_ns_list = array( 'Seaf\\Component' );

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
            'isRegistered' => 'has'
        ));

        /**
         * DIにじぶんを登録する
         */
        $this->di->register('environment', $this);

        /**
         * 自分のネームスーペース+\\Componentをコンポーネントネームスペースとして
         * 登録する
         */
        $caller_ns = substr( get_class($this), 0, strrpos(get_class($this), '\\') );
        if ( $caller_ns != __NAMESPACE__ ) {
            $this->addComponentNamespace(__NAMESPACE__.'\\Component' );
        }
        $this->addComponentNamespace( $caller_ns.'\\Component' );
    }

    public function __construct( )
    {
        $this->init();
    }

    /**
     * EnvNameを設定する
     */
    public function setEnvMode( $mode )
    {
        $this->mode_name = $mode;
    }

    /**
     * EnvNameを取得する
     */
    public function getEnvMode(  )
    {
        if( is_null($this->mode_name) ) return Seaf::getEnvMode( );
        return $this->mode_name;
    }


    /**
     * コンポーネントのネームスペースを追加する
     */
    public function addComponentNamespace ( $ns )
    {
        array_unshift( $this->component_ns_list, $ns );
        return $this->component_ns_list;
    }

    /**
     * コンポーネント取得メソッド
     */
    public function di ( $name )
    {
        // DIに登録されていればそれを呼び出す
        if( $this->di->has( $name ) ) {
            return $this->di->get( $name );
        }

        // そうでなければコンポーネントネームスペースから探す
        foreach ( $this->component_ns_list as $ns ) {

            $class = rtrim($ns,'\\').'\\'.ucfirst( $name );

            // Component\Yaml\Yamlみたいな奴を解決する
            $class2 = $class.'\\'.ucfirst($name);


            foreach ( array( $class, $class2 ) as $class ) {

                if ( class_exists( $class ) ) {
                    $this->register( $name, $class );
                    return $this->di->get( $name );
                }
            }
        }

        return false;
    }


    /**
     * コールハンドラ
     */
    public function call ( $name, $params ) 
    {
        return call_user_func_array( array( $this, $name ), $params );
    }

    /**
     * ヘルパメソッドを有効にする
     */
    public function __call ( $name, $params )
    {
        if( $this->helper->has( $name ) ) {
            return $this->helper->invokeArgs( $name, $params );
        }

        // コンポーネントを呼び出す
        if ( $comp = $this->di( $name ) ) {
            return $comp;
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
