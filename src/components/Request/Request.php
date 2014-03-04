<?php
/**
 * Seaf: Simple Easy Acceptable micro-framework.
 *
 * @author HAjime MATSUMOTO <mail@hazime.org>
 * @copyright Copyright (c) 2014, Seaf
 * @license   MIT, http://seaf.hazime.org
 */

namespace Seaf\Component\Request;

/**
 * リクエストコンポーネント
 */
class Request implements RequestIF
{
    private $url,$params,$method = 'GET';
    private $baseURL = '/';

    public function __construct ( $url = null, $params = array(), $method = 'GET' )
    {
        $this->url = $url;
        $this->params = $params;
        $this->method = $method;
    }

    /**
     * 新しいリクエストを作成する
     */
    public function newSimpleRequest ( $url, $params, $method = 'GET' )
    {
        return new self( $url, $params, $method );
    }

    /**
     * ベースURLを取得する
     * @return string
     */
    public function getBaseURL ()
    {
        return $this->baseURL;
    }

    /**
     * URLを取得する
     * @return string
     */
    public function getURL()
    {
        return $this->url;
    }

    /**
     * メソッドを取得する
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }
}

/* vim: set expandtab ts=4 sw=4 sts=4: et*/
