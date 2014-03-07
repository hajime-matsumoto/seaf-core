<?php
/**
 * Seaf: Simple Easy Acceptable micro-framework.
 *
 * コンテナを定義する
 *
 * キー、バリュー方式でストアされるデータ
 * を格納する基礎クラス
 *
 * @author HAjime MATSUMOTO <mail@hazime.org>
 * @copyright Copyright (c) 2014, Seaf
 * @license   MIT, http://seaf.hazime.org
 */

namespace Seaf\Core\Base;

use Iterator;

/**
 * コンテナの基礎クラス
 */
class Container implements Iterator
{
    protected $data;

    public function __construct( $default = array() )
    {
        $this->init( $default );
    }

    public function init( $default = array() )
    {
        $this->data = $default;
    }


    public function set( $key, $value = null)
    {
        if( is_array($key) && $value == null )
        {
            foreach($key as $k=>$v) $this->set($k,$v);
            return;
        }

        if( $this->has( $key ) )
        {
            throw new KeyAlreadyUsed($key);
        }

        return $this->_set( $key, $value );
    }

    public function get( $key, $default = null)
    {
        if (!$this->has($key)) 
        {
            return $default;
        }

        return $this->_get( $key, $default );
    }

    public function del( $key )
    {
        if (!$this->has($key)) 
        {
            throw new KeyNotExists($key);
        }

        unset($this->data[$key]);
    }

    public function update( $key, $value )
    {
        $this->_set( $key, $value );
    }

    public function has($key)
    {
        return $this->_has($key);
    }

    protected function _set( $key, $value )
    {
        $this->data[$key] = $value;
    }

    protected function _get($key, $default)
    {
        return $this->data[$key];
    }

    protected function _has($key)
    {
        return isset($this->data[$key]);
    }

    public function toArray( )
    {
        return $this->data;
    }

    public function rewind( )
    {
        reset( $this->data );
    }

    public function valid( )
    {
        return current($this->data);
    }

    public function next( )
    {
        return next($this->data);
    }

    public function current( )
    {
        return current($this->data);
    }

    public function key()
    {
        return key($this->data);
    }
}

class KeyAlreadyUsed extends \Exception
{
    public function __construct( $key )
    {
        parent::__construct( sprintf( "%sはすでに使用されています", $key ) );
    }
}
class KeyNotExists extends \Exception
{
    public function __construct( $key )
    {
        parent::__construct( sprintf( "%sは存在しないキーです", $key ) );
    }
}

/* vim: set expandtab ts=4 sw=4 sts=4: et*/
