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
use Seaf\Component\Request\RequestIF;

/**
 * ルータコンポーネント
 */
class Router
{
    /**
     * ルータオブジェクトたち
     * @var array
     */
    private $routes;

    /**
     * マウントしているオブジェクト達
     * @var array
     */
    private $mount;

    /**
     * ルーティング中に使用するインデックス
     * @var int
     */
    private $idx = 0;

    public function __construct ( )
    {
        $this->init();
    }

    public function init()
    {
        $this->routes = array();
        $this->mount  = array();
    }

    /**
     * マウントする
     *
     * @param string
     * @param object
     */
    public function mount( $path, $app )
    {
        $this->mount[$path] = $app;
    }

    /**
     * ルートを作成する
     *
     * @param mixed $pattern 配列で複数渡せる
     * @param callback 
     */
    public function map( $pattern, $callback = null )
    {
        if( is_array($pattern) && $callback == null )
        {
            foreach( $pattern as $k => $v )
            {
                $this->map( $k, $v );
            }
            return;
        }

        if (strpos($pattern, ' ') !== false) 
        {
            list($method, $url) = explode( ' ', trim($pattern), 2);
            $methods = explode( '|', $method);
            array_push($this->routes, new Route($url, $callback, $methods));
        }
        else
        {
            array_push($this->routes, new Route($pattern, $callback, array('*')));
        }
    }

    /**
     * ルートを取得する
     */
    public function route( RequestIF $request )
    {
        $url = $request->getURL();
        for( ; $this->idx<count($this->routes); $this->idx++)
        {
            $route = $this->routes[$this->idx];
            $isMatch = 
                $route->matchMethod($request->getMethod()) &&
                $route->matchURL($request->getURL());
            if( $isMatch )
            {
                return $route;
            }
        }

        // マウントがあるか調べる
        foreach( $this->mount as $path=>$app )
        {
            if( strpos($url,$path) === 0 ){
                $app->request()->setBaseURL(
                    $request->getBaseURL() == '/' ? $path: $request->getBaseURL().$path
                );
                return $app->run();
            }
        }

        return false;
    }

    /**
     * ルートをひとつ進める
     */
    public function next()
    {
        $this->idx++;
    }

    /**
     * ルートインデックスを初期化
     */
    public function reset()
    {
        $this->idx = 0;
    }
}


/**
 * ルートを保存するオブジェクト
 */
class Route
{
    /**
     * pattern
     * @var string
     */
    private $pattern;

    /**
     * @var mixed
     */
    public $callback;

    /**
     * @var string
     */
    private $methods;

    /**
     * @var array
     */
    public $params = array();

    /**
     * @var string
     */
    public $regex;

    /**
     * @var string
     */
    public $splat;


    /**
     * @param string
     * @param closure
     * @param array
     */
    public function __construct( $pattern, $callback, $methods ) 
    {
        $this->pattern = $pattern;
        $this->callback = $callback;
        $this->methods = $methods;
        $this->params = array();
    }

    public function getCallback( )
    {
        $callback = $this->callback;
        $params = $this->params;

        return function() use( $callback, $params ) {
            return call_user_func_array( $callback, $params );
        };
    }

    /**
     * @param string
     * @return bool
     */
    public function matchMethod( $method ) 
    {
        return count(
            array_intersect(
                array($method, '*'),
                $this->methods
            )
        ) > 0;
    }



    /**
     * @param string
     * @return bool
     */
    public function matchUrl($url) 
    {
        if( $this->pattern === "*" || $this->pattern === $url ) return true;

        $ids = array();
        $char = substr($this->pattern, -1);
        $this->splat = substr($url, strpos($this->pattern, '*'));

        $this->pattern = str_replace(
            array(')','*'), 
            array(')?','.*?'),
            $this->pattern
        );

        $regex = preg_replace_callback(
            '#@([\w]+)(:([^/\(\)]*))?#',
            function($matches) use (&$ids){
                $ids[$matches[1]] = null;
                if( isset($matches[3]) ){
                    return '(?P<'.$matches[1].'>'.$matches[3].')';
                }
                return '(?P<'.$matches[1].'>[^/\?]+)';
            }, $this->pattern
            );

        if ($char === '/' ) 
        {
            $regex .= '?';
        }
        else
        {
            $regex .= '/?';
        }


        if( preg_match('#^'.$regex.'(?:\?.*)?$#i', $url, $matches) )
        {
            foreach ($ids as $k=>$v) 
            {
                $this->params[$k] = 
                    (array_key_exists($k, $matches)) ? urldecode($matches[$k]): null;
            }
            $this->regex = $regex;
            return true;
        }
        return false;
    }

    public function invoke( )
    {
        return call_user_func( $this->getCallback( ) );
    }
}

/* vim: set expandtab ts=4 sw=4 sts=4: et*/
