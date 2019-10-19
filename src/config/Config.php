<?php
##version
#
namespace bear\sys\config;

//配置项处理
use bear\sys\config\build\Base;

/**
 * 配置
 * Class Config
 *
 * @package houdunwang\config
 */
class Config
{
    protected static $link = null;
    


    public function __call($method, $params)
    {
        return call_user_func_array([self::single(), $method], $params);
    }

    public static function single()
    {
        if (is_null(self::$link)) {
            self::$link = new Base();
        }
        // if(!is_null(self::$link)&&is_null(self::$loadFiles)){
            // self::$loadFiles = self::$link->loadFiles(__DIR__.'/../../config');##配置文件目录
        // }
        return self::$link;
    }

    public static function __callStatic($name, $arguments)
    {
        return call_user_func_array([self::single(), $name], $arguments);
    }
}