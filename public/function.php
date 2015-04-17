<?php

/**
 * 获取客户端IP地址
 * @param integer $type 返回类型 0 返回IP地址 1 返回IPV4地址数字
 * @param boolean $adv 是否进行高级模式获取（有可能被伪装）
 * @return mixed
 */
function get_client_ip($type = 0, $adv = false)
{
    $type = $type ? 1 : 0;
    static $ip = NULL;
    if ($ip !== NULL) return $ip[$type];
    if ($adv) {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $pos = array_search('unknown', $arr);
            if (false !== $pos) unset($arr[$pos]);
            $ip = trim($arr[0]);
        } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
    } elseif (isset($_SERVER['REMOTE_ADDR'])) {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    // IP地址合法验证
    $long = sprintf("%u", ip2long($ip));
    $ip = $long ? array($ip, $long) : array('0.0.0.0', 0);
    return $ip[$type];
}

/**
 * 发送HTTP状态
 * @param integer $code 状态码
 * @return void
 */
function send_http_status($code)
{
    static $_status = array(
        // Success 2xx
        200 => 'OK',
        // Redirection 3xx
        301 => 'Moved Permanently',
        302 => 'Moved Temporarily ', // 1.1
        // Client Error 4xx
        400 => 'Bad Request',
        403 => 'Forbidden',
        404 => 'Not Found',
        // Server Error 5xx
        500 => 'Internal Server Error',
        503 => 'Service Unavailable',
    );
    if (isset($_status[$code])) {
        header('HTTP/1.1 ' . $code . ' ' . $_status[$code]);
        // 确保FastCGI模式下正常
        header('Status:' . $code . ' ' . $_status[$code]);
    }
}

/**
 * j简单计算统计时间
 * @param bool $init
 * @return string
 */
function run_time($init = false)
{
    static $time = 0;

    if ($init) {
        $time = microtime(true);
    } else {
        return sprintf('%.2f', microtime(true) - $time);
    }
}

/**
 * 逗号分隔开的字符串变更为数组
 * @param string $id_str
 * @return array
 */
function commaToArray($id_str)
{
    $id_str = clearComma($id_str);
    $id_arr = explode(',', $id_str);
    return clearOneArray($id_arr);
}

/**检查URL地址
 * @return bool
 */
function checkUrl()
{
    $la = $_GET['language'];
    unset($_GET['language']);
    if ($_GET) {
        $base_md5 = $_GET['md5'];
        unset($_GET['md5']);
        $url = http_build_query($_GET);
        $url .= 'com.usitrip.www.fool';
        if ($base_md5 == md5($url)) {
            return true;
        } else {
            return false;
        }
    }
    $_GET['language'] = $la;
    return true;
}

/**
 * 把中文的逗号之类的给替换成英文的
 * @param string $str
 * @return string
 */

function clearComma($str)
{
    $str = str_replace('，', ',', $str);
    $str = str_replace('。', ',', $str);
    $str = str_replace('.', ',', $str);
    $str = str_replace(',,', ',', $str);
    return $str;
}

/**
 * 清楚掉一维数组空的字段
 * @param array $arr
 * @return array
 */

function clearOneArray($arr)
{
    foreach ($arr as $key => $value) {
        if (!$value) {
            unset($arr[$key]);
        }
    }
    return $arr;
}

/**
 * 二维数组降为一维数组
 * @param array $arr
 * @param string $k
 * @return array
 */

function downArray($arr, $k)
{
    $return = array();
    if (!$arr) return;
    foreach ($arr as $key => $value) {
        $return[] = $value[$k];
    }
    return $return;
}


/**
 * 生成一个符合本系统的超级链接地址
 * @param string $page  			文件名(如index.php,product.php etc.)
 * @param string $parameters  	参数(如cPath=1_4&products_id=1)
 * @param string $connection  	连接方式(SSL or NONSSL)
 * @param bool $add_session_id
 * @param bool $search_engine_safe
 * @param bool $for_seo 是否启用seo模式默认启用，如果不启用seo模式时直接调用href_link_noseo
 * @return string
 */
function href_link_check($page = '', $parameters = '', $connection = 'NONSSL', $add_session_id = false, $search_engine_safe = false, $for_seo = true){
    if($parameters){
        $md5=md5($parameters.'com.usitrip.www.fool');
        $parameters.='&md5='.$md5;
    }
    return html::href_link_admin($page, $parameters, $connection, $add_session_id, $search_engine_safe,$for_seo);
}

/**生成href
 * @param array $out
 * @return string
 */
function href_link($out=array()){
    $url='/'.$_GET['mod'];
    $url.='/'.$_GET['action'];
    if($url=='/index/index')
        return '/';
    foreach($_GET as $key=>$value){
        if(!in_array($key,$out))
            $url.='/'.$key.'/'.$value;
    }
    return $url;
}
/**
 * 字符串转ASCII数字串
 * @param $str 任意字符串
 * @param string $separator 生成数字串后的分隔符
 * @return string
 */
function string2ascii($str,$separator=" "){
    if($str=="") return false;
    $array=array();
    for($i=0; $i< strlen($str); $i++){
        $CurrentStr = $str[$i];
        if(ord($str[$i])>127){
            $CurrentStr = $str[$i].$str[++$i];
        }
        $a = (unpack("C*",$CurrentStr));
        $array[]=implode('',$a);
    }
    return implode($separator,$array);
}

/**
 * ASCII数字串转字符串
 * @param string $num_str 源数字串
 * @param string $separator 分割符
 * @return bool|string
 */
function ascii2string($num_str, $separator=" "){
    if(!(int)$num_str) return false;
    $array = explode($separator, $num_str);
    $str = "";
    foreach($array as $val){
        if(strlen($val)==6){
            $str.=chr(substr($val,0,3)).chr(substr($val,3,3));
        }else{
            $str.=chr($val);
        }
    }
    return $str;
}

/**
 * 跳转URL
 * @param $url
 */
function redirect($url){
    header('Location: ' . $url);
}
function create_link($mod,$action='',$param=''){
    if($param){
        $return = strtr(array('&'=>'/','='=>'/'),$param);
    }
    $url=(IS_ADMIN?'/admin':'').'/'.$mod.($action?'/'.$action:'').'/'.$return;
    return $url;

}