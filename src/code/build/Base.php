<?php

namespace cylcode\tools\code\build;

/**
 * 常用封培训代码管理
 * Class code
 *
 */
class Base
{
    /**
     * [strToVars 字符串中的{name}转成变量，并返出]
     * @Author   Jerry                    (wx621201)
     * @DateTime 2019-06-27T11:44:18+0800
     * @Example  eg:
     * @param    [type]                   $str       [description]
     * @param    [type]                   $vars      [description]
     * @return   [type]                              [description]
     *demo   $str = '我今天{$time}去{$address}  $vars = ['time'=>'2019-2-5','address'=>'人民广场']'
     */
    public function strToVars($str,$vars){
        preg_match_all('/(?<=\{)([^\}]*?)(?=\})/' , $str , $match);
            if(is_array($match[0])){
                foreach ($match[0] as $khref => $var) {
                    $varValue = isset($vars[$var])?$vars[$var]:'';##变量值
                    $str= str_replace('{'.$var.'}',$varValue,$str);
                }
                return $str;
            }

    }
}