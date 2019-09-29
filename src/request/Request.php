<?php
##version

namespace bear\sys\request;

//请求处理
use bear\sys\request\build\Base;

class Request
{
    protected static $link;

    public static function single()
    {
        if ( ! self::$link) {
            self::$link = new Base();
        }

        return self::$link;
    }

    public function __call($method, $params)
    {
        return call_user_func_array([self::single(), $method], $params);
    }

    public static function __callStatic($name, $arguments)
    {
        return call_user_func_array([self::single(), $name], $arguments);
    }
}