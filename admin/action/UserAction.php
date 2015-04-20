<?php
/**
 * Created by PhpStorm.
 * User: WangTieJun
 * Date: 2015/4/20
 * Time: 13:14
 */

class UserAction extends Action{
    function indexAction(){
        $admin=new Administrator();
        $this->user_list=$admin->getList($_GET);
        $this->_tpl='user_list.html';
        print_r($this->_data);
    }
    function changePasswordAction(){

    }
    function addUserAction(){

    }
    function changeAction(){

    }
    function deleteAction(){

    }

    /**
     * 初始化密码
     */
    function initializationPasswordAction(){

    }
}