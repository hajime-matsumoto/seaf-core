<?php
/**
 * Seaf: Simple Easy Acceptable micro-framework.
 *
 * クラスを定義する
 *
 * @author HAjime MATSUMOTO <mail@hazime.org>
 * @copyright Copyright (c) 2014, Seaf
 * @license   MIT, http://seaf.hazime.org
 */

namespace Seaf\Component;

use Seaf;
use Seaf\Core\Base\Container;

/**
 * Config
 */
class Config
{
    const DEFAULT_SECTION = 'default';

    private $section_list = array();
    private $env_name = Seaf::ENV_DEVELOPMENT;

    public function __construct ( )
    {
        $this->init( );
    }

    public function init ( )
    {
        $this->section_list = array( );
    }

    public function load ($file)
    {
        if ( substr($file,strrpos($file,'.')+1) == 'yaml' ) {
            $array = Seaf::yaml()->parse( $file );
        }

        if ( substr($file,strrpos($file,'.')+1) == 'php' ) {
            $array = include $file;
        }

        // セクションへ格納する
        foreach ( $array as $section=>$data ) {
            $this->section_list[$section] = new ConfigSection($section,$data);
        }
    }

    public function get( $key, $default = false )
    {
        $target = $this->section_list[$this->env_name];
        $default_section = $this->section_list[self::DEFAULT_SECTION];

        if ( $target->has($key) ) return $target->get($key);
        if ( $default_section->has($key) ) return $default_section->get($key);

        return $default;
    }

    public function set($key,$value)
    {
        $this->section_list[$this->env_name]->set($key,$value);
    }

    public function setEnvName( $name )
    {
        $this->env_name = $name;
    }
}

class ConfigContainer extends Container
{
    /**
     * .区切りを許す
     */
    public function get( $name, $default = false )
    {
        if ( false !== strpos($name,'.') ) {
            $token = strtok( $name, '.' );
            $data = parent::_get($token,$name);
            while ( $token = strtok('.') ) {
                $data = $data[$token];
            }
            return $data;
        }else{
            return parent::get($name,$default);
        }
    }

    public function set( $name, $value )
    {
        if ( false !== strpos($name,'.') ) {
            $token = strtok( $name, '.' );
            $key = $token;
            $data = parent::has($token) ? parent::_get($token,false): array();
            $head =& $data;
            while ( $token = strtok('.') ) {
                if ( !isset($data[$token]) ) {
                    $data[$token] = array();
                }
                $data =& $data[$token];
            }
            $data = $value;
            parent::_set($key,$head);
        }else{
            return parent::_set($name,$value);
        }
    }

    /**
     * データの存在を確認
     */
    public function has( $key )
    {
        if ( false !== strpos($key,'.') ) {
            $token = strtok( $key, '.' );

            if ( !parent::has( $token ) ) return false;

            $data = parent::_get($token, false);

            while ( $token = strtok('.') ) {
                if (!isset($data[$token])) {
                    return false;
                }
                $data = $data[$token];
            }
            return true;
        }
        return parent::has($key);
    }
}

class ConfigSection extends ConfigContainer
{
    private $name;

    public function __construct( $name, $data )
    {
        $this->name = $name;
        parent::__construct( $data );
    }
}

/* vim: set expandtab ts=4 sw=4 sts=4: et*/
