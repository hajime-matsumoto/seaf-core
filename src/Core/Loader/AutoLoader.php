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

namespace Seaf\Core\Loader;

/**
 * オートローダ
 */
class Autoloader
{
    private static $instance;
    private $ns_list = array();
    private $path_list = array();

    /**
     * シングルトンインスタンスを返す
     */
    public static function init( )
    {
        if( self::$instance ) return self::$instance;
        return self::$instance = new self();
    }

    /**
     *
     */
    public function __construct( )
    {
        spl_autoload_register( array($this,'seaf') );
        spl_autoload_register( array($this,'library') );
    }

    /**
     * ライブラリパスを追加する
     */
    public function addLibraryPath( $path )
    {
        $this->path_list[$path] = $path;
    }

    /**
     * ネームスペースを追加する
     */
    public function addNamespace( $namespace, $script, $path )
    {
        $this->ns_list[] = array(
            'ns' => $namespace,
            'scripts' => is_array($script) ? $script: array($script),
            'path' => $path
        );
    }


    /**
     * ライブラリを検索する
     */
    public function library( $class )
    {
        $pathList = $this->path_list;

        foreach( $pathList as $path )
        {
            $filename = $path.'/'.str_replace('\\','/', $class );
            $filenames = array( $filename.".php", $filename."/".ucfirst(basename($filename)).".php" );

            foreach ( $filenames as $file ) {
                if( file_exists($file) ) {
                    require_once $file;
                    return true;
                }
            }
        }
    }

    /**
     * SEAFライブラリを検索する
     */
    public function seaf( $class )
    {
        /**
         * 一致する定義を取得
         */
        $nsList = new NameSpaceListIterator( $this->ns_list, $class );

        foreach( $nsList as $ns=>$path )
        {
            $filename = $path.'/'.str_replace('\\','/', $basename = substr($class,strlen($ns)));
            $filenames = array( $filename.".php", $filename."/".ucfirst(basename($filename)).".php" );

            foreach ( $filenames as $file ) {
                if( file_exists($file) ) {
                    require_once $file;
                    return true;
                }
            }
        }
    }
}

/**
 * Name Space を探す
 */
class NameSpaceListIterator implements \Iterator
{
    private $ns_list = array();
    private $ns_list_position = 0;
    private $needle;

    public function __construct( array $nsList, $class )
    {
        $this->ns_list = $nsList;
        $this->needle = $class;
    }

    public function rewind( )
    {
        $this->ns_list_position = 0;
    }

    public function valid( )
    {
        $list = $this->ns_list;
        $needle = $this->needle;

        for ( $i = $this->ns_list_position; $i<count($list); $i++ )
        {
            $info = $list[$i];

            // 一致する
            if ( false !== ($p = strpos($needle, $info['ns']) ) )
            {
                if (!empty($info['scripts'])) 
                {
                    foreach ($info['scripts'] as $file)
                    {
                        if (!empty($file))
                        {
                            require_once $file;
                        }
                    }
                }

                $this->ns_list_position = $i;
                return true;
            }
        }
        return false;
    }


    public function current( )
    {
        return $this->ns_list[ $this->ns_list_position ]['path'];
    }

    public function key( )
    {
        return $this->ns_list[$this->ns_list_position]['ns'];
    }


    public function next( )
    {
        ++$this->ns_list_position;
    }
}

/* vim: set expandtab ts=4 sw=4 sts=4: et*/
