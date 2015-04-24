<?php

class UserAction extends Action{
    function indexAction(){
        $admin=new Administrator();
        $this->user_list=$admin->getList($_GET);
        $this->_tpl='user_list.html';
    }
    function changePasswordAction(){
        $this->_tpl='input_pass.html';
    }
    function checkPassWordAction(){
        $password=$_POST['pass_word'];
        $admin=new Administrator();
        $info=$admin->getOne($_SESSION['admin']['id']);
        $login=new Login;
        if($login->checkLogin($password,$info['admin_pwd']))
        {
            redirect('/admin/user/newPassword');
        }
    }
    function newPasswordAction(){
        $this->_tpl='new_password.html';
    }
    function doChangePassword(){
        $password=$_POST['pass_word'];
        $login=new Login();
        $password=$login->createPassword($password);
        $admin=new Administrator();
        $admin->changeOneFiled('admin_pwd',$password,$_SESSION['admin']['id']);
    }
    function addUserAction(){
        $this->_tpl='user_add.html';
    }
    function changeAction(){
        $admin=new Administrator();
        $this->info=$admin->getOne($_GET['user_id']);
        $group=new Group();
        $this->group_list=$group->getList();
        $this->_tpl='user_add.html';
    }
    function deleteAction(){
        $admin=new Administrator();
        $admin->delete((int)$_GET['user_id']);
        $page_name=$_GET['page']?'/page/'.$_GET['page']:'';
        redirect('/admin/user'.$page_name);
    }

    /**
     * 初始化密码
     */
    function initializationPasswordAction(){
        $change_password='123456';
        $admin_id=$_GET['user_id'];
        $login=new Login();
        $password=$login->createPassword($change_password);
        $admin = new Administrator();
        $admin->changeOneFiled('admin_pwd',$password,(int)$admin_id);
    }
}