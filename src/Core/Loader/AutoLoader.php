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

require_once dirname(__FILE__).'/NameSpaceListIterator.php';

/**
 * オートローダ
 */
class AutoLoader
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
     *
     * @return int パスリストの数
     */
    public function addLibraryPath( $path )
    {
        $this->path_list[$path] = $path;

        return count($this->path_list);
    }

    /**
     * ネームスペースを追加する
     *
     * @return int ネームスペースリストの数
     */
    public function addNamespace( $namespace, $script, $path )
    {
        $this->ns_list[] = array(
            'ns' => $namespace,
            'scripts' => is_array($script) ? $script: array($script),
            'path' => $path
        );

        return count($this->ns_list);
    }


    /**
     * ライブラリを検索する
     */
    public function library ($class, $dry_run=false)
    {
        $pathList = $this->path_list;

        foreach( $pathList as $path )
        {
            $filename = $path.'/'.str_replace('\\','/', $class );
            $filenames = array( $filename.".php", $filename."/".ucfirst(basename($filename)).".php" );

            foreach ( $filenames as $file ) {
                if( file_exists($file) ) {
                    if ($dry_run === true) {
                        return $file;
                    }
                    require_once $file;
                    return true;
                }
            }
        }
    }

    /**
     * SEAFライブラリを検索する
     */
    public function seaf($class, $dry_run=false)
    {
        /**
         * 一致する定義を取得
         */
        $nsList = new NameSpaceListIterator( $this->ns_list, $class );

        foreach( $nsList as $ns=>$path )
        {
            $filename = $path.'/'.ltrim(
                str_replace('\\','/', $basename = substr($class,strlen($ns)))
                ,'/'
            );

            $filenames = array(
                $filename.".php",
                $filename."/".ucfirst(basename($filename)).".php"
            );

            foreach ( $filenames as $file ) {
                if( file_exists($file) ) {
                    if ($dry_run === true) {
                        return $file;
                    }
                    require_once $file;
                    return true;
                }
            }
        }
    }
}
/* vim: set expandtab ts=4 sw=4 sts=4: et*/
