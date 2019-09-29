<?php
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use cylcode\manager\Exception;
    

if ( ! function_exists('readCsv')) {
  /**
   * [readCsv 读取CSV]
   * @Author   Jerry                    (c84133883)
   * @DateTime 2019-09-27T10:46:32+0800
   * @Example  eg:
   * @param    [type]                   $path       [全路径地址: ./public/attachement/201909221522.csv]
   * @param    boolean                  $formatData [是否格式化数据返出，默认把第0个数据段设置为表头]
   * @return   [type]                               [description]
   */
  function readCsv($path,$formatData=false){

    setlocale(LC_ALL,'zh_CN');//linux系统下生效
    $data = null;//返回的文件数据行
    if(!is_file($path)&&!file_exists($path))
    {
       throw new \cylcode\manager\Exception\BearException("加载文件发生错误：");
         
        
    }
    $cvs_file = fopen($path,'r'); //开始读取csv文件数据
    $i = 0;//记录cvs的行
    while ($file_data = fgetcsv($cvs_file))
    {
      // show($file_data);
        if($file_data){
            ##转码
          foreach ($file_data as &$vdata) {
            $vdata =  $vdata?iconv('gb2312','utf-8',$vdata):''; 
          }
          $data[] = $file_data;
          
        }
 
    }

    fclose($cvs_file);
    ##转码
     if($formatData){
           $listData  = [];
           ##表头数据
           $header = $data[0];
           unset($data[0]);
           ##表列数据
            foreach ($data as $k => $v) {
              foreach ($header as $kHeader => $vHeader) {
                $listData[$k][$vHeader] = $v[$kHeader];
              }
              
            }
            $datas['header'] = $header;
            $datas['list'] = $listData;
        }
        return $formatData?$datas:$data;
    return $data;
  }


}
if ( ! function_exists('saveCsv')) {
  /**
   * [saveCsv 保存CSV]
   * @Author   Jerry                    (c84133883)
   * @DateTime 2019-09-27T10:35:15+0800
   * @Example  eg:
   * @return   [type]                   [description]
   */
  function saveCsv(){
     // 头部标题
    $csv_header = array('sku','我们自己的成本价','京东自己的销售价','对比结果');
 
    /**
     * 开始生成
     * 1. 首先将数组拆分成以逗号（注意需要英文）分割的字符串
     * 2. 然后加上每行的换行符号，这里建议直接使用PHP的预定义
     * 常量PHP_EOL
     * 3. 最后写入文件
     */
  // 打开文件资源，不存在则创建
      $des_file = 'd:/res.csv';
      $fp = fopen(    $des_file,'a');
  // 处理头部标题
      $header = implode(',', $csv_header) . PHP_EOL;
  // 处理内容
      $content = '';
      foreach ($csv_body as $k => $v) {
          $content .= implode(',', $v) . PHP_EOL;
      }
  // 拼接
      $csv = $header.$content;
  // 写入并关闭资源
      fwrite($fp, $csv);
      fclose($fp);

  }
}
if ( ! function_exists('readExcel')) {
  /**
   * [readExcel 读取EXCEL信息，需要包phpoffice/phpexcel支持]
   * @Author   Jerry                    (c84133883)
   * @DateTime 2019-09-25T10:46:08+0800
   * @Example  eg:
   * @param    [type]                   $path       [全路径地址: ./public/attachement/201909221522.xlxs]
   * @param    string                   $maxRow     [最大行值:   15(行的数值) ]
   * @param    string                   $maxColumn  [最大列值:   R(列的字母值)]
   * @param    integer                  $sheet      [读取哪个sheet表 ，默认第0个]
   * @param    bool                     $fomatData  [是否格式化数据返出，默认把第0个数据段设置为表头]
   * @return   [type]                               [description]
   */
  function readExcel($path,$maxRow='',$maxColumn='',$sheet=0,$formatData=false){
        if(!file_exists($path)) throw new \Exception($path." 文件不存在", 1);
        // 读取excel文件
        try {
          $inputFileType = PHPExcel_IOFactory::identify($path);
          $objReader = PHPExcel_IOFactory::createReader($inputFileType);
          $objPHPExcel = $objReader->load($path);
        } catch(Exception $e) {
          throw new \cylcode\manager\Exception\BearException("加载文件发生错误：".pathinfo($inputFileName,PATHINFO_BASENAME).$e->getMessage(), 1);
        }
        // 确定要读取的sheet，什么是sheet，看excel的右下角，真的不懂去百度吧
        $sheet = $objPHPExcel->getSheet($sheet);
        $highestRow = $maxRow?$maxRow:$sheet->getHighestRow();
        $highestColumn = $maxColumn?$maxColumn:$sheet->getHighestColumn();

        $rowData = [];
        // 获取一行的数据
        for ($row = 1; $row <= $highestRow; $row++){
           $rowData[] = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE)[0];
        }
        //格式化数据返出，默认把第0个数据段设置为表头
        if($formatData){
           $listData  = [];
           ##表头数据
           $header = $rowData[0];
           unset($rowData[0]);
           ##表列数据
            foreach ($rowData as $k => $v) {
              foreach ($header as $kHeader => $vHeader) {
                $listData[$k][$vHeader] = $v[$kHeader];
              }
              
            }
            $data['header'] = $header;
            $data['list'] = $listData;
        }
        return $formatData?$data:$rowData;
  }
}
if ( ! function_exists('saveExcel')) {
  /**
   * [saveExcel 保存为EXCEL]
   * @Author   Jerry                    (c84133883)
   * @DateTime 2019-09-25T10:24:17+0800
   * @Example  eg:
   * @return   [type]                   [description]
   */
  function saveExcel(){
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setCellValue('A1', 'Welcome to Helloweba.');
    
    $writer = new Xlsx($spreadsheet);
    $writer->save('hello.xlsx');
  }
}
/**
     * [token description]
     * @Author   Jerry
     * @DateTime 2018-09-04T09:54:36+0800
     * @Example  eg:
     * @param    string                   $name [description]
     * @return   [type]                         [description]
     */
     function token($name = '__token__')
    {

        $token = Crypt::encrypt($_SERVER['REQUEST_TIME_FLOAT']);
        if (Bear::isAjax()||Bear::isPost()) {
            header($name . ': ' . $token);
        }
        Session::set($name,$token);
        return $token;
    }




if ( ! function_exists('getInput')) {
    /**
     * [get description]
     * @Author   Jerry
     * @DateTime 2018-08-20T09:28:35+0800
     * @Example  eg:
     * @param    [type]                   $name [description]
     * @return   [type]                         [description]
     */
    function getInput($key = '', $default = null, $filter = ''){
         if (0 === strpos($key, '?')) {
            $key = substr($key, 1);
            $has = true;
        }
        if ($pos = strpos($key, '.')) {
            // 指定参数来源
            list($method, $key) = explode('.', $key, 2);
            if (!in_array($method, ['get', 'post', 'put', 'patch', 'delete', 'route', 'param', 'request', 'session', 'cookie', 'server', 'env', 'path', 'file'])) {
                $key    = $method . '.' . $key;
                $method = 'param';
            }
        } else {
            // 默认为自动判断
            $method = 'param';
        }
        // $request = new \bear\sys\request\Request;
        //
        // if (isset($has)) {
        //     return \bear\sys\request\Request::has($key, $method, $default);
        // } else {
        //     return \bear\sys\request\Request::$method($key, $default, $filter);
        // }
    }
}

/**
* [show description]
* @Author   Jerry
* @DateTime 2018-08-16T11:24:46+0800
* @Example  eg:
* @param    [type]                   $var [description]
* @return   [type]                        [description]
*/
 function show($var){
    if (is_bool($var)) {
           var_dump($var);
       } else if (is_null($var)) {
           var_dump(NULL);
       } else {
           echo "<pre style='padding:10px;border-radius:5px;background:#F5F5F5;border:1px solid #aaa;font-size:14px;line-height:18px;'>" . print_r($var, true) . "</pre>";
       }
}
/**
 * [returnJson description]
 * @Author   Jerry                    (c84133883)
 * @DateTime 2019-09-20T11:51:47+0800
 * @Example  eg:
 * @param    integer                  $status     [状态码]
 * @param    [type]                   $msg        [信息]
 * @param    array                    $data       [附加DATA信息]
 * @param    boolean                  $extra      [附加UR信息]
 * @param    array                    $merge      [附加合并信息]
 * @return   [type]                               [description]
 */
 function returnJson($status=0,$msg=null,$data=[],$extra=true,$merge=[]){

    if($extra){
       $data['href'] = isset($data['href'])?$data['href']:'JavaScript:history.go(-1)';##,_parentReload:关闭当前窗口，刷新父级 ,JavaScript:history.go(-1):返回上一页
      $data['target'] =  isset($data['target'])?$data['target']:'_self';##_blank：新窗口打开,_self:当前窗口打开
    }
    $arrayData = [
   'status'=>(int)$status,
   'msg'   =>is_null($msg)?Config::get("msg.msg.{$status}"):($msg?$msg:''), ##当MSG为NULL时，MSG不会选择配置项里面的信息
   'data'  =>$data?$data:'',
   ];
   //合并信息
    if(!empty($merge)){
      $arrayData = array_merge($arrayData,$merge); 
    }
    echo  json_encode($arrayData,JSON_UNESCAPED_UNICODE);

   exit() ;
}

/**
 * [errorJson description]
 * @Author   Jerry                    (wx621201)
 * @DateTime 2019-05-30T09:59:54+0800
 * @Example  eg:
 */
function errorJson($status=0,$msg=null,$data=[]){
    $data['href'] = isset($data['href'])?$data['href']:'JavaScript:history.go(-1)';##,_parentReload:关闭当前窗口，刷新父级 ,JavaScript:history.go(-1):返回上一页
   $data['target'] =  isset($data['target'])?$data['target']:'';'_self';##_blank：新窗口打开,_self:当前窗口打开
    echo  json_encode([
   'status'=>(int)$status,
   'msg'   =>is_null($msg)?Config::get("msg.msg.{$status}"):($msg?$msg:''), ##当MSG为NULL时，MSG不会选择配置项里面的信息
   'data'  =>$data?$data:'',
   ],JSON_UNESCAPED_UNICODE);
   die() ;
}
/**
* [isPost 判断是否为post]
* @Author   Jerry
* @DateTime 2018-08-20T16:39:58+0800
* @Example  eg:
* @return   boolean                  [description]
*/
 function isPost()
{
  return isset($_SERVER['REQUEST_METHOD']) && strtoupper($_SERVER['REQUEST_METHOD'])=='POST';
}

/**
* [isGet description]
* @Author   Jerry
* @DateTime 2018-08-20T16:39:13+0800
* @Example  eg:
* @return   boolean                  [description]
*/
 function isGet()
{
  return isset($_SERVER['REQUEST_METHOD']) && strtoupper($_SERVER['REQUEST_METHOD'])=='GET';
}

/**
* [is_ajax description]
* @Author   Jerry
* @DateTime 2018-08-20T16:39:39+0800
* @Example  eg:
* @return   boolean                  [description]
*/
 function isAjax()
{
   return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtoupper($_SERVER['HTTP_X_REQUESTED_WITH'])=='XMLHTTPREQUEST';
}
/**
* [is_cli description]
* @Author   Jerry
* @DateTime 2018-08-20T16:39:51+0800
* @Example  eg:
* @return   boolean                  [description]
*/
 function isCli()
{
   return (PHP_SAPI === 'cli' OR defined('STDIN'));
}

/**
 * 简单对称加密算法之加密
 * @param String $string 需要加密的字串
 * @param String $skey 加密EKY
 * @return String 加密后的字符串
 * @author Anyon Zou <cxphp@qq.com>
 */
function encode($string = '', $skey = '6f918e') {
    $skey = str_split(base64_encode($skey));
    $strArr = str_split(base64_encode($string));
    $strCount = count($strArr);
    foreach ($skey as $key => $value) {
        $key < $strCount && $strArr[$key].=$value;
    }
    return str_replace('=', '6f918e', join('', $strArr));
}

/**
 * 简单对称加密算法之解密
 * @param String $string 需要解密的字串
 * @param String $skey 解密KEY
 * @return String 解密后的字符串
 * @author Anyon Zou <cxphp@qq.com>
 */
function decode($string = '', $skey = '6f918e') {
    $skey = str_split(base64_encode($skey));
    $strArr = str_split(str_replace('6f918e', '=', $string), 2);
    $strCount = count($strArr);
    foreach ($skey as $key => $value) {
        if ($key < $strCount && $strArr[$key][1] === $value) {
            $strArr[$key] = $strArr[$key][0];
        } else {
            break;
        }
    }
    return base64_decode(join('', $strArr));
}