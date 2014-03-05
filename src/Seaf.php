<?php
/**
 * Seaf: Simple Easy Acceptable micro-framework.
 *
 * Seafのメインクラスを定義する
 *
 * @author HAjime MATSUMOTO <mail@hazime.org>
 * @copyright Copyright (c) 2014, Seaf
 * @license   MIT, http://seaf.hazime.org
 */


use Seaf\Core\Environment\Environment;
use Seaf\Core\Loader\AutoLoader;


/**
 * Seafメインクラス
 */
class Seaf
{
    /**
     * 定数の定義
     */
    const ENV_DEVELOPMENT='development';
    const ENV_PRODUCTION='production';

    /**
     * @var object
     */
    static private $instance;

    /**
     * @var object
     */
    private $env;

    /**
     * 外部から呼び出さない用にコンストラクタは
     * privateにする
     */
    private function __construct ( )
    {
        $this->env = new Environment( );

        // オートローダを登録する
        $this->env->register( 'autoLoader', function( ) {
            return AutoLoader::init( );
        });

        $this->env->setEnvMode( self::ENV_DEVELOPMENT );
    }

    /**
     * シングルトンインスタンスを取得する
     *
     * @return object
     */
    static public function getInstance ( )
    {
        if (self::$instance) return self::$instance;

        self::$instance = new Seaf( );
        return self::$instance;
    }

    /**
     * @param name  $name 
     * @param array  $params params
     * @return mixed
     */
    static public function __callStatic ( $name, array $params = array( ) ) 
    {
        $seaf = self::getInstance();

        return $seaf->env->call( $name, $params );
    }
}

/* vim: set expandtab ts=4 sw=4 sts=4: et*/
