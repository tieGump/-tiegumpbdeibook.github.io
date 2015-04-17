<?php

/**登录
 * Class LoginAction
 * @author tie.Gump
 */
class LoginAction extends Action{
    /**
     * 检查登录的
     */
    function sessionAction(){
        if($_POST['session']){
//	        $_POST['session'] = str_replace('\"','"',$_POST['session']);
            $_SESSION = json_decode($_POST['session'],true);
            if(isset($_SESSION['admin'])){
                echo 1;
//                header("Location: /usiadmin/staff_train/index/index");
            }else{
                echo 2;
//                header("Location: /usiadmin/index.php");
            }
//
        }
    }

    /**
     * 跳到登录页面
     */
    function upAction(){
        $this->_tpl='login.html';
    }

    /**
     * 登出
     */
    function offAction(){
        unset($_SESSION);
        die('成功登出');
    }
}