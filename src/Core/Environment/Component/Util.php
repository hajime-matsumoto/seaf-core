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

namespace Seaf\Core\Environment\Component;

/**
 * Utilコンポーネント
 * ===================
 *
 *  Seaf::util()->getNamespace($object)
 */
class Util
{
    public function getNamespace ($class)
    {
        if (is_object($class)) {
            $class = get_class($class);
        }
        $ns = substr($class, 0, strrpos($class, '\\'));
        return $ns;
    }

    public function arrayGet (array $array, $key, $default = null)
    {
        if (false !== strpos($key,'.')) {
            $token = strtok($key,'.');
            do {
                if (!isset($current[$token])) return $default;
                $current =& $current[$token];
            } while ($token = strtok($key,'.'));

            return $current;
        }

        return array_key_exists($key, $array) ?
            $array[$key]:
            $default;
    }
}

/* vim: set expandtab ts=4 sw=4 sts=4: et*/
