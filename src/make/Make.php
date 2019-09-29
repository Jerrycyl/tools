<?php
##version

namespace bear\sys\make;


use bear\sys\make\build\Base;

/**
 * Cookie 管理组件
 * Class Cookie
 *
 * @package 
 */
class Make
{
    protected static $link;

    public function __call($method, $params)
    {
        if (is_null(self::$link)) {
            self::$link = new Base();
        }

        return call_user_func_array([self::$link, $method], $params);
    }

    public static function single()
    {
        static $link;
        if (is_null($link)) {
            $link = new static();
        }

        return $link;
    }

    public static function __callStatic($name, $arguments)
    {
        return call_user_func_array([static::single(), $name], $arguments);
    }
}