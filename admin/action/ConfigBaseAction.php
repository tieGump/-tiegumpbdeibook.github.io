<?php
class ConfigBaseAction extends Action{

    function indexAction(){
        $config=new ConfigBase();
        $list=$config->getList();
        foreach($list as $value){
            $need[$value['config_unique_name']]=$value;
        }
        $this->config_info=$need;
        $this->_tpl='base_config.html';
    }
    function doEditAction(){
        $config=new ConfigBase();
        $config->update($_POST);
        redirect('/admin/ConfigBase');
    }
}