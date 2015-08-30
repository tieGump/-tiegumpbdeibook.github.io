<?php
//目录定义开始
define('BASE_DIR',dirname(ROOT).'/');
define('ACTION_DIR',ROOT.'action/');//动作路径
define('SRC_DIR',BASE_DIR.'src/'); //类路径
define('SRC_LOG',BASE_DIR.'logs/'); //日志
define('CACHE_DIR',BASE_DIR.'cache/');//缓存路径
define('PHP_CACHE_DIR',CACHE_DIR.'php/'); //php缓存路径
define('HTML_CACHE_DIR',CACHE_DIR.'html/');//html 缓存路径 暂时没用
define('PUBLIC_DIR',BASE_DIR.'public/');//公开文件路径
define('TPL_DIR',ROOT.'tpl/');//模板路径
define('TPL_CACHE_DIR',BASE_DIR.'tpl_cache/');//模板缓存路径
define('IS_ADMIN',1);//定义是后台
define('TPL_C',ROOT.'/tpl_c/');//模板生成路径
define('SMARTY_TPL_DIR',PUBLIC_DIR.'smarty/');//smarty路径
define('UPLOAD_DIR',BASE_DIR.'upload/');//上传路径
define('PAGE_BASE','/admin');
//目录定义结束

//数据库信息开始
define('DB_SERVER', 'localhost');						//数据库地址
define('DB_SERVER_USERNAME', 'root');				//数据库用户名
define('DB_SERVER_PASSWORD', '');	//数据库密码
define('DB_DATABASE', 'bdei_book');	//默认选择的数据库名称
//数据库信息结束

define('IS_BUG',false);//是否是测试
define('IS_PHP_CACHE',true);//是否开启PHP缓存
define('IS_HTML_CACHE',true);//开启HTML缓存
