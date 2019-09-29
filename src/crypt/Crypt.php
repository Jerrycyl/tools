<?php
##version
namespace bear\sys\crypt;
use bear\sys\config\Config;
use bear\sys\crypt\build\Base;

class Crypt
{
    protected static $link;

    //获取实例
    protected function driver()
    {
        self::$link = new Base();

        return $this;
    }

    public function __call($method, $params)
    {
        if ( ! self::$link) {
            $this->driver();
        }

        return call_user_func_array([self::$link, $method], $params);
    }

    public static function single()
    {
        static $link = null;
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