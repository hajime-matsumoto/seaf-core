<?php
/**
 * Seaf: Simple Easy Acceptable micro-framework.
 *
 * DIコンテナを定義する
 *
 * @author HAjime MATSUMOTO <mail@hazime.org>
 * @copyright Copyright (c) 2014, Seaf
 * @license   MIT, http://seaf.hazime.org
 */

namespace Seaf\Core\DI;

use Seaf\Core\Base\Container;
use Seaf\Core\Factory\Factory;
use Seaf\Core\Environment\EnvironmentAcceptableIF;

/**
 * 環境クラス
 */
class DIContainer extends Container
{
    private $factory;

    public function __construct( Factory $factory  = null )
    {
        if ($factory == null) {
            $this->factory = new Factory( );
        }else{
            $this->factory = $factory;
        }
    }

    /**
     * コンポーネントを登録する
     *
     * 第二引数がインスタンスでなければ
     * ファクトリに登録する
     *
     * @param string
     * @param mixed
     * @param callback
     */
    public function register( $name, $object, $cb = null)
    {
        if ( !is_callable($object) && is_object($object) ) {
            return parent::set($name, $object);
        }
        return $this->factory->set( $name, $object, $cb );
    }

    /**
     * 存在するか
     */
    public function has( $name )
    {
        if ($this->factory->has($name)) return true;
        return parent::has($name);
    }

    /**
     * 取得する
     */
    public function get($name)
    {
        if (!$this->has($name)) {
            throw new UndefinedComponent($name);
        }

        if (parent::has($name)) return parent::get($name);

        $instance = $this->factory->create($name);

        if ( $instance instanceof EnvironmentAcceptableIF ) {
            $instance->acceptEnvironment( $this->get('environment') );
        }

        parent::update($name,$instance);
        return $instance;
    }


    public function __invoke($name, $params = array())
    {
        array_unshift($params,$name);
        return call_user_func_array(
            array($this,'get'),
            $params);
    }
}

/* vim: set expandtab ts=4 sw=4 sts=4: et*/
