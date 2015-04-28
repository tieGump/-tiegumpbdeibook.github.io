<?php

class DisableIPAction extends Action {
    function indexAction(){
        $ip=new DisableIP();
        $this->ip_list=$ip->getList();
        $this->_tpl='ip_list.html';
    }
    function deleteAction(){
        $ip=new DisableIP();
        $ip->delete($_GET['ip_id']);
        redirect('/admin/disableIP');
    }
    function addAction(){
        $this->_tpl='ip_add.html';
    }
    function doUpdateAction(){
        $ip=new DisableIP();
        if(isset($_POST['ip_id'])&&$_POST['ip_id']){
            $mark=$ip->change($_POST,(int)$_POST['ip_id']);
        }else{
            $mark=$ip->add($_POST);
        }
        if($mark){
            $this->_message->addSession('操作成功','success');
        }else{
            $this->_message->addSession('操作失败');
        }
        redirect('/admin/disableIP');
    }
    function editAction(){
        $ip_id=(int)$_GET['ip_id'];
        $ip=new DisableIP();
        $this->one_info=$ip->getOne($ip_id);
        $this->_tpl='ip_add.html';
    }
} 