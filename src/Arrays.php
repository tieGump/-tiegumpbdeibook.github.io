<?php
/**
 * 字符串函数升级为可处理数组的类，这个东西作用大！
 * @package    扩展库
 * @subpackage 数组扩展
 */
class Arrays {


    /**
     * 静态属性保存实例化的对象
     * @var arrays
     */
    private static $Objects = null;

    /**
     * 字符串函数升级为可处理数组的类
     * @example 如果使用的方法不在本类方法列表中就采用如下方法处理，如：
     * <pre>
     * arrays->str_shuffle('hi, hello');
     * arrays->str_split('hi, hello');
     * </pre>
     */
    private function __construct() {
    }

    /**
     * 静态调用本类
     * {@source }
     */
    public static function getStatic() {
        if (empty(self::$Objects)) {
            self::$Objects = new self();
        }
        return self::$Objects;
    }

    /**
     * 用于生成ID 对于值的数组
     * @param $arr
     * @param $k
     * @param $v
     * @return array
     */
    public static function  arrayKeyToValue($arr, $k, $v) {
        $return = array();
        foreach ($arr as $value)
            $return[$value[$k]] = $value[$v];
        return $return;
    }

    /**
     * 清楚掉一维数组空的字段
     * @param array $arr
     * @return array
     */
    public static function clearOneArray($arr) {
        return array_filter($arr);
    }

    /**
     * 逗号分隔开的字符串变更为数组
     * @param string $id_str
     * @author tieGump
     * @return array
     */
    public static function commaToArray($id_str) {
        $id_str = Strings::clearComma($id_str);
        $id_arr = explode(',', $id_str);
        return arrays::clearOneArray($id_arr);
    }

    /**
     * 此方法只能在5.3以后使用，呜呼！
     * @param string $method_name
     * @param array  $args
     * @return array|string
     */
    public static function __callStatic($method_name, $args) {
        $arg0 = $args[0];
        unset($args[0]);
        return self::_maps($method_name, $arg0, (array) $args);
    }

    /**
     * 如果使用的方法不在本类方法列表中就采用此方法处理
     * @param string $method_name
     * @param array  $args
     * @return Ambigous <multitype:, string, 主参数(array)，要调用的函数中的第1个参数($arg0)，可以是数组或字符串, mixed>
     */
    public function __call($method_name, $args) {
        $arg0 = $args[0];
        unset($args[0]);
        return self::_maps($method_name, $arg0, (array) $args);
    }

    /**
     * 本类的方法流程管道，大多数方法都调用此方法来实现其功能
     * @param string       $function_name 方法名称
     * @param array|string $str_array     主参数，要调用的函数中的第1个参数($arg0)，可以是数组或字符串
     * @param array        $others_arg    其它参数，如array($arg1, $arg2, $arg3[,.......])
     * @throws Exception
     * @return array|string
     */
    private static function _maps($function_name, $str_array, $others_arg = array()) {
        if (!function_exists($function_name))
            throw new Exception('系统无这样的函数！' . $function_name . '()');
        if (is_string($str_array)) {
            $refFunc        = new ReflectionFunction($function_name); //调用函数的反射类
            $paramMaxNum    = $refFunc->getNumberOfParameters(); //取得该函数允许的参数个数
            $args[]         = $str_array;
            $others_arg_len = count($others_arg);
            for ($i = 1, $n = $paramMaxNum; $i < $n; $i++) {
                if ($i > $others_arg_len) {
                    break;
                }
                if ($others_arg[($i - 1)]) {
                    $args[] = $others_arg[($i - 1)];
                }
            }
            $str_array = call_user_func_array($function_name, $args);
        } elseif (is_array($str_array)) {
            reset($str_array);
            while (list($key, $value) = each($str_array)) {
                $str_array[$key] = self::_maps($function_name, $value, $others_arg);
            }
        }
        return $str_array;
    }

    /**
     * 清除数组中值的首尾空格（或者其他字符）
     * @param array|string $string_or_array 待处理的字符串或数组
     * @param string       $charlist        可选参数，过滤字符也可由 charlist 参数指定。
     *                                      如果不指定第二个参数，trim() 将去除这些字符：
     *                                      " " (ASCII 32 (0x20))，普通空格符。
     *                                      "\t" (ASCII 9 (0x09))，制表符。
     *                                      "\n" (ASCII 10 (0x0A))，换行符。
     *                                      "\r" (ASCII 13 (0x0D))，回车符。
     *                                      "\0" (ASCII 0 (0x00))，空字节符。
     *                                      "\x0B" (ASCII 11 (0x0B))，垂直制表符。
     * @return array|string 返回处理后的数据
     */
    public static function trim($string_or_array, $charlist = '') {
        if ($charlist == '') {
            return self::_maps(__FUNCTION__, $string_or_array);
        }
        return self::_maps(__FUNCTION__, $string_or_array, array($charlist));
    }

    /**
     * 清除数组每个值中的前面空格（或者其他字符）
     * @param array|string $string_or_array 待处理的字符串或数组
     * @param string       $charlist        可选参数，过滤字符也可由 charlist 参数指定。
     * @return array|string 返回处理后的数据
     */
    public static function ltrim($string_or_array, $charlist = '') {
        if ($charlist == '') {
            return self::_maps(__FUNCTION__, $string_or_array);
        }
        return self::_maps(__FUNCTION__, $string_or_array, array($charlist));
    }

    /**
     * 清除数组每个值中的末尾的空格（或者其他字符）
     * @param array|string $string_or_array 待处理的字符串或数组
     * @param string       $charlist        可选参数，过滤字符也可由 charlist 参数指定。
     * @return array|string 返回处理后的数据
     */
    public static function rtrim($string_or_array, $charlist = '') {
        if ($charlist == '') {
            return self::_maps(__FUNCTION__, $string_or_array);
        }
        return self::_maps(__FUNCTION__, $string_or_array, array($charlist));
    }

    /**
     * 数组内容编码转换
     * @param string       $in_charset  原文编码
     * @param string       $out_charset 输出的编码
     * @param array|string $str_array   原文数组或字符串
     * @return array|string
     */
    public static function iconv($in_charset, $out_charset, $str_array) {
        if (is_string($str_array)) {
            // 遇到转换时，内容过多会自动截取掉的问题。
            if(mb_strlen($str_array) > 10000) {
                $temp = '';
                for($i=0,$len=ceil(mb_strlen($str_array) / 10000);$i<$len;$i++) {
                    $temp .= iconv($in_charset,str_replace('//IGNORE', '', $out_charset) . '//IGNORE',mb_substr($str_array,$i*10000,10000));
                }
                return $temp;
            } else {
                return iconv($in_charset, str_replace('//IGNORE', '', $out_charset) . '//IGNORE', $str_array);
            }
        } elseif (is_array($str_array)) {
            reset($str_array);
            while (list($key, $value) = each($str_array)) {
                $str_array[$key] = self::iconv($in_charset, $out_charset, $value);
            }
        }
        return $str_array;
    }

    /**
     * 将数组字符串转化为小写
     * @param array|string $str_array
     * @return array|string
     */
    public static function strtolower($str_array) {
        return self::_maps(__FUNCTION__, $str_array);
    }

    /**
     * 将数组字符串转化为大写
     * @param array|string $str_array
     * @return array|string
     */
    public static function strtoupper($str_array) {
        return self::_maps(__FUNCTION__, $str_array);
    }

    /**
     * 将数组每个元素值的首字母转换为大写
     * @param array|string $str_array
     * @return array|string
     */
    public static function ucfirst($str_array) {
        return self::_maps(__FUNCTION__, $str_array);
    }

    /**
     * 将数组每个元素值的首字母转换为小写
     * @param array|string $str_array
     * @return array|string
     */
    public static function lcfirst($str_array) {
        return self::_maps(__FUNCTION__, $str_array);
    }

    /**
     * 将字符串中每个单词的首字母转换为大写
     * @param array|string $str_array
     * @return array|string
     */
    public static function ucwords($str_array) {
        return self::_maps(__FUNCTION__, $str_array);
    }

    /**
     * 在字符串所有新行之前插入 HTML 换行标记
     * @param array|string $str_array 字符串
     * @param bool         $is_xhtml  是否使用 XHTML 兼容换行符 php5.3.0新增的参数
     * @return array|string
     */
    public static function nl2br($str_array, $is_xhtml = true) {
        return self::_maps(__FUNCTION__, $str_array, array($is_xhtml));
    }

    /**
     * 把数组或字符串变量中的一些预定义的字符转换为HTML实体
     * 预定义的字符是：
     * & （和号） 成为 &amp;
     * " （双引号） 成为 &quot;
     * ' （单引号） 成为 &#039;
     * < （小于） 成为 &lt;
     * > （大于） 成为 &gt;
     * @param array|string $str_array     规定要转换的字符串或数组
     * @param int          $flags         规定如何编码单引号和双引号 ENT_COMPAT - （默认）仅编码双引号；ENT_QUOTES - 编码双引号和单引号；ENT_NOQUOTES - 不编码任何引号。
     * @param string       $charset       字符串值，规定要使用的字符集，如：UTF-8、GB2312、BIG5等，默认是ISO-8859-1。具体查手册，php5.4以后默认为utf-8
     * @param bool         $double_encode 当double_encode被关闭PHP将现有的HTML实体编码，默认为转换所有（google翻译的，未测试不知准不准。哈哈）。php5.2.3新增
     * @return array|string
     */
    public static function htmlspecialchars($str_array, $flags = ENT_COMPAT, $charset = 'ISO-8859-1', $double_encode = true) {
        return self::_maps(__FUNCTION__, $str_array, array($flags, $charset, $double_encode));
    }

    /**
     * 从数组(字符串)中去除 HTML 和 PHP 标记
     * @param array|string $str_array      源字符串或数组
     * @param string       $allowable_tags 允许保留的html字符列表
     * @return array|string
     */
    public static function strip_tags($str_array, $allowable_tags = '') {
        return self::_maps(__FUNCTION__, $str_array, array($allowable_tags));
    }

    /**
     * 对已编码的 URL 字符串进行解码
     * @param array|string $str_array 源字符串或数组
     * @return string|array
     */
    public static function rawurldecode($str_array) {
        return self::_maps(__FUNCTION__, $str_array);
    }

    /**
     * rawurlencode编码
     * @param $str_array
     * @return array|string
     */
    public static function rawurlencode($str_array) {
        return self::_maps(__FUNCTION__, $str_array);
    }

    public static function stripslashes($str_array) {
        return self::_maps(__FUNCTION__, $str_array);
    }

    public static function tep_output_string($str_array) {
        return self::_maps(__FUNCTION__, $str_array);
    }

    /**
     * 无限级格式树结构
     * @param array  $items  要被格式化的数据
     * @param string $pidKey 父id的键(字段)名
     * @param string $uidKey 主键id的键(字段)名
     * @param string $sonKey 存储子元素的键名
     * @return array
     */
    public static function tree(array $items, $pidKey, $uidKey, $sonKey = 'son') {
        $tree = array(); //格式化好的树
        foreach ($items as $item) {
            if (isset($items[$item[$pidKey]])) {
                $items[$item[$pidKey]][$sonKey][] = & $items[$item[$uidKey]];
            } else {
                $tree[] = & $items[$item[$uidKey]];
            }
        }
        return $tree;
    }

    /**
     * 将数组的值格式化成整数
     * @param     $var  要转换的目标
     * @param int $base 源进制标识
     * @return array|string
     */
    public static function intval($var, $base = 0) {
        return self::_maps(__FUNCTION__, $var, $base);
    }

    /**
     * 二维数组降为一维数组
     * @param array  $arr 二维数组
     * @param string $k   $key标识，如'name'就是把$arr中的key=name的值集合成一维数组
     * @return array 返回处理后的一维数组，如果源数组不是二维数组的话则返回不作任何处理的源数组
     */
    public static function downArray(array $arr, $k) {
        if (self::getLevel($arr) >= 2) {
            $return = array();
            foreach ((array) $arr as $key => $value) {
                $return[$key] = $value[$k];
            }
            return $return;
        } else {
            return $arr;
        }
    }

    /**
     * 返回数组的维度
     * @param  array $arr 源数组
     * @return int 返回维度，如二维数组就返回2
     */
    public static function getLevel($arr) {
        $al = array(0);
        if (!function_exists('tmpF_aL_Arrays_Zhh')) {
            function tmpF_aL_Arrays_Zhh($arr, &$al, $level = 0) {
                if (is_array($arr)) {
                    $level++;
                    $al[] = $level;
                    foreach ($arr as $v) {
                        tmpF_aL_Arrays_Zhh($v, $al, $level);
                    }
                }
            }
        }
        tmpF_aL_Arrays_Zhh($arr, $al);
        return max($al);
    }

    /**
     * 重新设置数组键名从0,1...开始
     * @param array $array
     */
    public static function reSetNumKey(& $array = array()) {
        $a     = $array;
        $array = array();
        foreach ((array) $a as $_k => $v) {
            $array[] = $v;
        }
    }

    /**
     * 按给定的id去排序数组
     * @param array  $array       含有key名称为id的二维数组
     * @param string $sort_id_str 要排序的id字符串，如：215,335,21。按这个字符id的升序排序
     * @param string $key_name    默认为id,要拿来排序的key名称
     */
    public static function sortFromIds(&$array, $sort_id_str, $key_name = 'id') {
        $_array = array();
        foreach (explode(',', $sort_id_str) as $id) {
            foreach ($array as $i => $v) {
                if ($id == $array[$i][$key_name]) {
                    $_array[] = $array[$i];
                    unset($array[$i]);
                }
            }
        }
        $array = array_merge($_array, $array);
    }

    /**
     * 过滤多维数组中重复的值，并返回过滤后的一维数组
     * @param $array
     * @return array 返回处理后的一维数组
     */
    public static function array_unique_deep($array) {
        $values = array();
        //ideally there would be some is_array() testing for $array here...
        foreach ($array as $part) {
            if (is_array($part)) {
                $values = array_merge($values, self::array_unique_deep($part));
            } else {
                $values[] = $part;
            }
        }
        return array_unique($values);
    }

    /**
     * 对二维数组里的某个值进行排序
     * @param $arr
     * @param $key
     * @param string $type
     * @return array
     */
    public static function array_two_order_by($arr,$key,$type='DESC'){
        $tmp=arrays::downArray($arr,$key);
        if($type=='DESC'){
            arsort($tmp);
        }else{
            asort($tmp);
        }
        foreach($tmp as $key=>$value){
            $return[$key]=$arr[$key];
        }
        return $return;
    }

    /**
     * 多维数组排序,类似数据库中的orderby 字段1 排序方式  字段2 排序方式 ...
     * @param array $source_array 要排序的多维数组
     * @param array $arr          要排序的字段，格式array(array('item'=>'要排序的数组元素KEY名称','sort'=>'排序方式(asc,desc)')[,array('item'=>'再按第二个元素KEY名称','sort'=>'排序方式')[,...]])
     * @return mixed
     * @author lwkai
     * @date   2015-3-26
     */
    public static function multisort($source_array,array $arr){
        $data = array();
        foreach ($source_array as $row) {
            $i = 0;
            if(!is_array($data[$i])) {
                $data[$i] = array();
            }
            foreach($arr as $v) {
                $data[$i][] = $row[$v['item']];
                $data[$i+1] = (strtolower($v['sort']) == 'asc' ? SORT_ASC : SORT_DESC);
                $i = $i + 2;
            }
        }
        /*
        PHP 5.4之前，如果param_arr里面的参数是引用传值，那么不管原函数默认的各个参数是不是引用传值，都会以引用方式传入到回调函数。虽然以引用传值这种方式来传递参数给回调函数，不会发出不支持的警告，但是不管怎么说，这样做还是不被支持的。并且在PHP 5.4里面被去掉了。而且，这也不适用于内部函数，for which the function signature is honored。如果回调函数默认设置需要接受的参数是引用传递的时候，按值传递，结果将会输出一个警告。call_user_func() 将会返回 FALSE（there is, however, an exception for passed values with reference count = 1, such as in literals, as these can be turned into references without ill effects ? but also without writes to that value having any effect ?; do not rely in this behavior, though, as the reference count is an implementation detail and the soundness of this behavior is questionable）。

可以看出这是php特定版本中call_user_func()函数的问题，暂时尚未测试官方的5.4之后版本是否仍然存在该问题，当前版本下的修改方案只能是避免使用call_user_func()去调用类似的需要引用传参的内置函数
         */
        $args = array();
        for($i=0,$len=sizeof($data);$i < $len;$i++) {
            $args[] = &$data[$i];
        }
        if ($args) {
            $args[] = &$source_array;
            call_user_func_array("array_multisort",$args);
        }
        return $source_array;
    }
}
