<?php
/**动作的基础 主要实例化smarty的参数
 * Class Action
 * @author tie.Gump
 */
abstract class Action {
    protected $_data=array();
    protected $_smarty;
    protected $_tpl;
    protected $_message;
    function __construct(){
        require SMARTY_TPL_DIR.'/Smarty.class.php';
        require SMARTY_TPL_DIR.'/SmartyBC.class.php';
        $this->_smarty=new SmartyBC();
        $this->_smarty->allow_php_templates=false;
        $this->_smarty->php_handling=-1;
        $this->_smarty->template_dir = TPL_DIR.'/';        // 模板存放目录
        $this->_smarty->compile_dir = TPL_C.'/';      // 编译后文件存放目录
//        $this->_smarty->config_dir    = './configs/';              //
        $this->_smarty->cache_dir    = TPL_CACHE_DIR.'/';               // 缓存目录
        $this->_smarty->caching       = false;                      // 是否开启缓存
        $this->_smarty->left_delimiter = "{";
        $this->_smarty->right_delimiter = "}";
        $this->createCssUrl();
        $this->_message=new Message();
        $this->eofTop();
//        $this->_smarty->allow_php_tag=true;


//        $this->_smarty->php_handling = SMARTY_PHP_ALLOW;
    }

    /**
     * 生成css 跟模板的路径 这个地方主要有个深度的问题
     */
    function createCssUrl(){
        $path=isset($_SERVER['PATH_INFO'])?$_SERVER['PATH_INFO']:'';
        $tmp=explode('/',$path);

//        $tmp=clearOneArray($tmp);
        $j=count($tmp);
        $str='';
        for($i=2;$i<$j;$i++){
            $str.='../';
        }
        $this->dir=$str;
        $this->tpl=$str.'tpl/';
    }
    /**实现魔术set函数 对data进行赋值
     * @param $name
     * @param $value
     */
    function __set($name,$value){
        $this->_data[$name]=$value;
    }
    function __get($name){
        if($name=='tpl')
            return $this->_data[$name];
    }

    /**
     * 析构函数，实现smarty对模板赋值
     */
    function __destruct(){
        $this->get=$_GET;
        if(!$this->_message->checkFirst()){
            $this->_data['message']=$this->_message->getSessionMessage();
            $this->_message->clearSessionMessage();
        }else{
            $this->_data['message']='';
        }
        foreach($this->_data as $key=>$value){
            $this->_smarty->assign($key,$value);
        }

        if($this->_tpl){
            $this->_smarty->assign('get',$_GET);
            $this->_smarty->display($this->_tpl);
        }
    }
    function eofTop(){
        $tpl=$this->tpl;
        echo <<<EOF
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>==</title>
    <link rel="stylesheet" type="text/css" href="{$tpl}resources/css/style.css" />
    <!-- jQuery file -->
    <script src="{$tpl}js/jquery.min.js"></script>
    <script src="{$tpl}js/jquery.tabify.js" type="text/javascript" charset="utf-8"></script>
    <script type="text/javascript">
        var $ = jQuery.noConflict();
        $(function() {
            $('#tabsmenu').tabify();
            });

    </script>

</head>
EOF;


    }
} 