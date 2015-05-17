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
        if(isset($_SESSION['user']['id'])){
           $this->user_name=$_SESSION['user']['name'];
           $this->_tpl='login_in.html';
        }else{
            $this->_tpl='login.html';
        }

    }
    function doLoginAction(){
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
    function registerAction(){
        $this->_tpl='register.html';
    }
    function doRegisterAction(){
        $user=new User();
        if(!$user_id=$user->register($_POST)){
            $this->_message->addSession('注册失败！！！请从新注册！！！');
            redirect('/login/register');
        }else{
            echo <<<EOF
            <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
</head>

<body>
<script>
alert("注册成功!!!请登录！！！");
parent.pop.close();;
</script>
</body>
</html>
EOF;


        }

    }
    function findPasswordOneAction(){
        $this->_tpl='find_back1.html';
    }
    function findPasswordTwoAction(){
        $user=new User();
        $info=$user->getInfoByName($_POST['name']);
        $this->question=$info['question'];
        $this->user_name=$_SESSION['find_user']=$info['user_name'];
        $this->_tpl='find_back2.html';
    }
    function findPasswordThreeAction(){
        $user=new User();
        $info=$user->getInfoByName($_SESSION['find_user']);
        if($_POST['aq']==$info['answer']){
            $this->user_name=$info['user_name'];
            $this->_tpl='find_back3.html';
        }else{
            $this->_message->addSession('答案不正确');
            $this->findPasswordOneAction();
        }

    }
    function changePasswordAction(){
        print_r($_SESSION);
        $user=new User();
        if($user->changePassword($_SESSION['find_user'],$_POST['pass'])){
            echo <<<EOF
            <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
</head>

<body>
<script>
alert("密码修改成功!!!请登录！！！");
parent.pop.close();;
</script>
</body>
</html>
EOF;

        }
    }
    /**
     * 登出
     */
    function offAction(){
        unset($_SESSION['user']);
        if(isset($_GET['not_index']))
        redirect('/login');
        else
        redirect('/');
    }
}