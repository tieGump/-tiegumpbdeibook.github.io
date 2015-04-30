<?php

/**登录
 * Class LoginAction
 * @author tie.Gump
 */
class LoginAction extends Action{
    /**
     * 检查登录的
     */
    function indexAction(){
        $this->_tpl='login.html';
    }
    function doLogin(){
        $user_name=$_POST['user_name'];
        $password=$_POST['password'];
        $user=new User();
        if($user->login($user_name,$password)){
            $this->user_name=$_SESSION['user']['name'];
            $this->_tpl='login_in.html';
        }else{
            $this->_message->addSession('用户名或密码错误');
            redirect('/login');
        }
    }
    /**
     * 登出
     */
    function offAction(){
        unset($_SESSION);
        redirect('/login');
    }
}