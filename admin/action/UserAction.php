<?php

class UserAction extends Action{
    function indexAction(){
        $admin=new Administrator();
        $group=new Group();
        $group_list=$group->getList();
        $this->group_list=Arrays::arrayKeyToValue($group_list,'group_id','group_name');
        $this->user_list=$admin->getList($_GET);
        $this->_tpl='user_list.html';
    }
    function changePasswordAction(){
        $this->_tpl='input_pass.html';
    }
    function checkPassWordAction(){
        $password=$_POST['admin_pwd'];
        $admin=new Administrator();
        $info=$admin->getOne($_SESSION['admin']['id']);
        $login=new Login;
        if($login->checkLogin($password,$info['admin_pwd']))
        {
            redirect('/admin/user/newPassword');
        }else{
            $this->_message->addSession('密码不正确！！！请重新输入！！');
            redirect('/admin/user/changePassword');
        }
    }
    function newPasswordAction(){

        $this->_tpl='new_password.html';
    }
    function doChangePasswordAction(){
        if($_POST['admin_pwd']&&$_POST['admin_pwd_re']&&$_POST['admin_pwd']==$_POST['admin_pwd_re']){
            $this->_message->addSession('输入信息不正确！！！');
            redirect('/admin/user/newPassword');
        }
        $password=$_POST['admin_pwd'];
        $login=new Login();
        $password=$login->createPassword($password);
        $admin=new Administrator();
        $admin->changeOneFiled('admin_pwd',$password,$_SESSION['admin']['id']);
        $this->_message->addSession('修改密码成功！！！','success');
        redirect('/admin/user/changePassword');
    }
    function addUserAction(){
        $group=new Group();
        $this->group_list=$group->getList();
        $this->_tpl='user_add.html';
    }
    function doAddAction(){
        $user=new Administrator();
        $user->register($_POST);
        redirect('/admin/user');
    }
    function editAction(){
        $admin=new Administrator();
        $this->info=$tmp=$admin->getOne((int)$_GET['admin_id']);
        $group=new Group();
        $this->group_list=$group->getList();
        $this->_tpl='user_edit.html';
    }
    function doEditAction(){
        $admin=new Administrator();
        $data['admin_name']=$_POST['admin_name'];
        $data['group_id']=$_POST['group_id'];
        $admin->changeOne($data,(int)$_POST['admin_id']);
        redirect('/admin/user');
    }
    function deleteAction(){
        $admin=new Administrator();
        $admin->delete((int)$_GET['admin_id']);
        $page_name=$_GET['page']?'/page/'.$_GET['page']:'';
        $this->_message->addSession('删除成功！！！','success');
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