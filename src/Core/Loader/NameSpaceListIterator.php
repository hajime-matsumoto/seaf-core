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
 * ネームスペースリストイテレータ
 * ==============================
 *
 * 与えられたリスト、クラス名から
 * 一致するものだけをイテレーションする
 *
 */
class NameSpaceListIterator implements \Iterator
{
    /**
     * ネームスペースのリスト
     *
     * @var array
     */
    private $ns_list = array();

    /**
     * 現在のポジション
     *
     * @var int
     */
    private $ns_list_position = 0;

    /**
     * 検索対象のクラス名
     *
     * @var string
     */
    private $needle;

    /**
     * @param array $nsList
     * @param string $class
     */
    public function __construct( array $nsList, $class )
    {
        $this->ns_list = $nsList;
        $this->needle = $class;
    }

    /**
     * @assert () === null
     */
    public function rewind( )
    {
        $this->ns_list_position = 0;
    }

    /**
     * @assert () === true
     */
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
