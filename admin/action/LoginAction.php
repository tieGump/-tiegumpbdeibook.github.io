<?php
class LoginAction extends Action{
    /**
     * 登录界面
     */
    function upAction(){
        $this->_tpl='login.html';
    }

    /**
     * 处理登录
     */
    function doLoginAction(){
        $admin=new Administrator();
        if($admin->login($_POST['user_name'],$_POST['user_pwd'])){
            redirect('/admin');
        }else{
            $this->_message->addSession('用户名或密码错误！！！');
            redirect('/admin/login/up');
        }
    }

    /**
     * 登出
     */
    function loginOutAction(){
        $admin=new Administrator();
        $admin->loginOut();
        redirect('/admin/login/up');
    }
}