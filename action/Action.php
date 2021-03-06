<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-4-2
 * Time: 下午3:24
 */

/**动作的基础 主要实例化smarty的参数
 * Class Action
 * @author tie.Gump
 */
abstract class Action {
    protected $_data=array();
    protected $_smarty;
    protected $_tpl;
    protected $_message;
    private $_ip;
    function __construct(){
        $this->_ip=new DisableIP();
        if(!$this->_ip->check()){
            die('禁止访问！！！');
            exit;
        }

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
        $this->getConfig();
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
        $this->tpl=$str=$str.'tpl/';
        $this->tpl='/tpl/';
    }
    /**实现魔术set函数 对data进行赋值
     * @param $name
     * @param $value
     */
    function __set($name,$value){
        $this->_data[$name]=$value;
    }
    function __get($name){
//        if($name=='tpl')
            return $this->_data[$name];
    }
    function setSearchType(){
        $this->search_book_type=Book::getSearchType();
    }
    function getConfig(){
        $config=new ConfigBase();
        $this->_data['title_img']=$config->getOneValue('title_img');
        $this->_data['search_example']=$config->getOneValue('search_example');
        $this->_data['system_notice']=explode('||',$config->getOneValue('system_notice'));
    }
    /**
     * 析构函数，实现smarty对模板赋值
     */
    function __destruct(){

        $this->setSearchType();

        if(!$this->_message->checkFirst()){
            $this->_data['message']=$this->_message->getSessionMessage();
            $this->_message->clearSessionMessage();
        }else{
            $this->_data['message']='';
        }
        $file_name=str_replace('.html','',$this->_tpl);
        if(is_file(TPL_DIR.'css/'.$file_name.'.css')){
            $this->_data['base_css']=$this->tpl.'css/'.$file_name.'.css';
        }
        $this->get=$_GET;
        foreach($this->_data as $key=>$value){
            $this->_smarty->assign($key,$value);
        }
        if($this->_tpl){
            $this->_smarty->assign('get',$_GET);
            $this->_smarty->display($this->_tpl);
        }
    }
}