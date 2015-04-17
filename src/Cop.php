<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-3-24
 * Time: 下午1:30
 */
define('IS_CACHE',0);
class Cop {
    private static $_class_name=array();

    /**
     * 程序入口
     */
    static public function start(){
        //注册AUTOLOAD方法

        spl_autoload_register('Cop::autoload');
        // 设定错误和异常处理
        register_shutdown_function('Cop::fatalError');
        set_error_handler('Cop::appError');
        set_exception_handler('Cop::appException');
        $info=self::getPathInfo();
        $mod=new $info['mod'];
        $action=$info['action'].'Action';
//        var_dump($info);
        $mod->$action();
//        print_r($_SERVER['PATH_INFO']);

    }

    /**自动加载
     * @param $name
     */
    static function autoload($name){
        if(!isset(self::$_class_name[$name])&&!in_array($name,array('Smarty'))&&'Smarty'!=substr($name,0,6)){
            if(IS_CACHE){
                $str_path=self::get($name.'.php');
                require($str_path);
            }else{
                if(is_file(SRC_DIR.$name.'.php'))
                    require SRC_DIR.$name.'.php';
                elseif(is_file(ACTION_DIR.$name.'.php'))
                    require ACTION_DIR.$name.'.php';
            }

//
        }

    }

    /**处理路径信息
     * @return array
     */
    static function getPathInfo(){
        $path=isset($_SERVER['PATH_INFO'])?$_SERVER['PATH_INFO']:'';
        $tmp=explode('/',$path);

        $tmp=clearOneArray($tmp);
        $mod=isset($tmp[1])?$tmp[1]:'Index';
        $action=isset($tmp[2])?$tmp[2]:'Index';
        $j=count($tmp);
        for($i=3;$i<$j;$i++){
            $_GET[$tmp[$i]]=$tmp[++$i];
        }
        $_GET['mod']=$mod;
        $_GET['action']=$action;
        if(IS_ADMIN&&!$_SESSION['admin']['id']&&$_GET['mod']!='login'){
            $mod=$_GET['mod']='login';
            $action=$_GET['action']='up';
        }
        return array('mod'=>ucfirst($mod).'Action','action'=>($action));
    }
    /**
     * 取得对象实例 支持调用类的静态方法
     * @param string $class 对象类名
     * @param string $method 类的静态方法名
     * @return object
     */
    static public function instance($class,$method='') {
        $identify   =   $class.$method;
        if(!isset(self::$_instance[$identify])) {
            if(class_exists($class)){
                $o = new $class();
                if(!empty($method) && method_exists($o,$method))
                    self::$_instance[$identify] = call_user_func(array(&$o, $method));
                else
                    self::$_instance[$identify] = $o;
            }
            else
                self::halt(('_CLASS_NOT_EXIST_').':'.$class);
        }
        return self::$_instance[$identify];
    }

    /**
     * 自定义异常处理
     * @access public
     * @param mixed $e 异常对象
     */
    static public function appException($e) {
        $error = array();
        $error['message']   =   $e->getMessage();
        $trace              =   $e->getTrace();
        if('E'==$trace[0]['function']) {
            $error['file']  =   $trace[0]['file'];
            $error['line']  =   $trace[0]['line'];
        }else{
            $error['file']  =   $e->getFile();
            $error['line']  =   $e->getLine();
        }
        $error['trace']     =   $e->getTraceAsString();
        // 发送404信息
        header('HTTP/1.1 404 Not Found');
        header('Status:404 Not Found');
        self::halt($error);
    }

    /**
     * 自定义错误处理
     * @access public
     * @param int $errno 错误类型
     * @param string $errstr 错误信息
     * @param string $errfile 错误文件
     * @param int $errline 错误行数
     * @return void
     */
    static public function appError($errno, $errstr, $errfile, $errline) {
        switch ($errno) {
            case E_ERROR:
            case E_PARSE:
            case E_CORE_ERROR:
            case E_COMPILE_ERROR:
            case E_USER_ERROR:
                ob_end_clean();
                $errorStr = "$errstr ".$errfile." 第 $errline 行.";
                self::halt($errorStr);
                break;
            default:
                $errorStr = "[$errno] $errstr ".$errfile." 第 $errline 行.";
                break;
        }
    }

    // 致命错误捕获
    static public function fatalError() {
        if ($e = error_get_last()) {
            switch($e['type']){
                case E_ERROR:
                case E_PARSE:
                case E_CORE_ERROR:
                case E_COMPILE_ERROR:
                case E_USER_ERROR:
                    ob_end_clean();
                    self::halt($e);
                    break;
            }
        }
    }

    /**
     * 错误输出
     * @param mixed $error 错误
     * @return void
     */
    static public function halt($error) {
        $e = array();
        if (IS_BUG) {
            //调试模式下输出错误信息
            if (!is_array($error)) {
                $trace          = debug_backtrace();
                $e['message']   = $error;
                $e['file']      = $trace[0]['file'];
                $e['line']      = $trace[0]['line'];
                ob_start();
                debug_print_backtrace();
                $e['trace']     = ob_get_clean();
            } else {
                $e              = $error;
            }
            if(0){
                exit(iconv('UTF-8','gbk',$e['message']).PHP_EOL.'FILE: '.$e['file'].'('.$e['line'].')'.PHP_EOL.$e['trace']);
            }
        } else {

        }
    }

    /**获取文件路径
     * @param $file_name
     * @return string|void
     */
    static function getFilePath($file_name){
        return is_file(SRC_DIR . '/' . $file_name)?SRC_DIR . '/' . $file_name:(is_file(ACTION_DIR . '/' . $file_name)?ACTION_DIR . '/' . $file_name:die('不存在的类'));
    }

    /**文件缓存保存
     * @param $file_name
     * @param int $get
     */
    static function set($file_name,$get=0)
    {

        $file_path=self::getFilePath($file_name);
//        $caceh=new FileCache();
//        $caceh->set($file_name.'time',filemtime($file_name));
        require CACHE_DIR.'/cache.php';
        $data=$arr_cache;
        $data[md5($file_name.'time')]=filemtime($file_path);
        file_put_contents(CACHE_DIR.'/cache.php','<?php $arr_cache='.var_export($data,true).'; ',LOCK_EX);
        $content=file_get_contents($file_path);
//        $time='//'.str_pad(filemtime($file_path),20,' ');
        $content=self::stripComments($content);
//        $content=serialize($content);
//        $content=gzcompress($content,3);
        file_put_contents(PHP_CACHE_DIR.'/'.$file_name,$content);
        clearstatcache();
    }

    /**文件缓存获取
     * @param $file_name
     * @return string
     */
    static function get($file_name)
    {   $file_path=self::getFilePath($file_name);
        if(is_file(PHP_CACHE_DIR.'/'.$file_name)){
//            $content=file_get_contents(PHP_CACHE_DIR.'/'.$file_name);
//            $time=(int)substr($content,0,22);
            require CACHE_DIR.'/cache.php';
            $data=$arr_cache;
            $time=isset($data[md5($file_name.'time')])?$data[md5($file_name.'time')]:0;
            if(filemtime($file_path)!=$time){

                self::set($file_name);
                return PHP_CACHE_DIR.'/'.$file_name;
            }else{

//                $content=substr($content,20);
//                $content=gzuncompress($content);
//                $content=unserialize($content);
                return PHP_CACHE_DIR.'/'.$file_name;
            }
        }else{
            self::set($file_name);
            return PHP_CACHE_DIR.'/'.$file_name;
        }

    }

    /**去掉文件的注释
     * @param $source
     * @return mixed|string
     */
    static function stripComments($source)
    {
        if (!function_exists('token_get_all')) {
            return $source;
        }

        $output = '';
        foreach (token_get_all($source) as $token) {
            if (is_string($token)) {
                $output .= $token;
            } elseif (!in_array($token[0], array(T_COMMENT, T_DOC_COMMENT))) {
                $output .= $token[1];
            }
        }

        // replace multiple new lines with a single newline
        $output = preg_replace(array('/\s+$/Sm', '/\n+/S'), "\n", $output);

        return $output;
    }
}
