<?php
##version
namespace bear\sys\make\build;



class Base
{
    /**
     * [getSort 获得排序]
     * @Author   Jerry
     * @DateTime 2018-08-23T15:12:22+0800
     * @Example  eg:
     * @return   [type]                   [description]
     */
    public function getSort($sort=''){
        if(isset($_GET['orderfield'])&&isset($_GET['ordervalue'])){
            return htmlspecialchars(trim($_GET['orderfield'])).' '.htmlspecialchars(trim($_GET['ordervalue']));
        }else{
            return $sort;
        }
    }
}