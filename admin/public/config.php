<?php
//Ŀ¼���忪ʼ
define('BASE_DIR',dirname(ROOT).'/');
define('ACTION_DIR',ROOT.'action/');//����·��
define('SRC_DIR',BASE_DIR.'src/'); //��·��
define('CACHE_DIR',BASE_DIR.'cache/');//����·��
define('PHP_CACHE_DIR',CACHE_DIR.'php/'); //php����·��
define('HTML_CACHE_DIR',CACHE_DIR.'html/');//html ����·�� ��ʱû��
define('PUBLIC_DIR',BASE_DIR.'public/');//�����ļ�·��
define('TPL_DIR',ROOT.'tpl/');//ģ��·��
define('TPL_CACHE_DIR',BASE_DIR.'tpl_cache/');//ģ�建��·��
define('IS_ADMIN',1);//�����Ǻ�̨
define('TPL_C',ROOT.'/tpl_c/');//ģ������·��
define('SMARTY_TPL_DIR',PUBLIC_DIR.'smarty/');//smarty·��
define('UPLOAD_DIR','/var/www/html/usitrip');//�ϴ�·��F:\Work\newSite\usiadmin\staff_train\upload
//Ŀ¼�������

//���ݿ���Ϣ��ʼ
define('DB_SERVER', 'localhost');						//���ݿ��ַ
define('DB_SERVER_USERNAME', 'root');				//���ݿ��û���
define('DB_SERVER_PASSWORD', '123456');	//���ݿ�����
define('DB_DATABASE', 'bdei_book');	//Ĭ��ѡ������ݿ�����
//���ݿ���Ϣ����

define('IS_BUG',true);//�Ƿ��ǲ���
define('IS_PHP_CACHE',true);//�Ƿ���PHP����
define('IS_HTML_CACHE',true);//����HTML����
