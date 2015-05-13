<?php
class CustomerAction extends Action{
    function indexAction(){
        $user=new User();
        $this->user_list=$tmp=$user->getList();
        $this->_tpl='customer_list.html';
    }
    function addAction(){
        $this->_tpl='customer_add.html';
    }
    function updateAction(){
        $user=new User();
        $this->user_info=$user->getOne((int)$_GET['user_id']);
        $this->addAction();
    }
    function doChangeAction(){
        $user=new User();
        if($user->register($_POST,(int)$_POST['user_id'])){
            $this->_message->addSession('修改成功！！！','success');
        }else{
            $this->_message->addSession('修改失败！！！');
        }
        redirect('/admin/customer');
    }
    function deleteAction(){
        $user=new User();
        $user->delete((int)$_GET['user_id']);
        redirect('/admin/customer');
    }
}