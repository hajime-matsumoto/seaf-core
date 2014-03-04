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
 * リクエストコンポーネントのインターフェイス
 */
interface RequestIF
{
    /**
     * ベースURLを取得する
     * @return string
     */
    public function getBaseURL();

    /**
     * URLを取得する
     * @return string
     */
    public function getURL();

    /**
     * メソッドを取得する
     * @return string
     */
    public function getMethod();
}

/* vim: set expandtab ts=4 sw=4 sts=4: et*/
