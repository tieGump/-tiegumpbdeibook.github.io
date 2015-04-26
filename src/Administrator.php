<?php
/**
 *
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/4/9
 * Time: 11:03
 */

class Administrator extends Mode{
    function __construct(){
        $this->_table='bdei_administrator';
        $this->_table_id='admin_id';
        parent::__construct();
    }
    /**
     * 注册
     */
    function register($post){
        $login=new Login();
        $data['admin_name']=$post['admin_name'];
        $data['admin_pwd']=$login->createPassword($post['admin_pwd']);
        $data['group_id']=$post['group_id'];
        $data['add_time']=date('Y-m-d H:i:s');
        return $this->addOne($data);
    }
    function getList($get){
        $where='1';
        if(isset($get['user_name'])&&$get['user_name']){
            $where.=' AND admin_name LIKE "%'.$get['user_name'].'%"';
        }
        $str_sql='SELECT '.$this->_fields.' FROM '.$this->_table.' WHERE '.$where;
        return $this->getPageList($str_sql);
    }
    function changeInfo($post,$id){

        $data['user_nick']=$post['user_nick'];
        if($post['admin_pwd']&&$post['admin_pwd']==$post['admin_pwd_re']){
            $login=new Login();
            $data['admin_pwd']=$login->createPassword($post['admin_pwd']);
        }
        return $this->changeOne($data,$id);
    }
    function getInfoByName($name){
        return $this->getOne('admin_name="'.$name.'"');
    }
    function login($user_name,$password){
        $info=$this->getInfoByName($user_name);
        $login=new Login;
        if($login->checkLogin($password,$info['admin_pwd'])){
            $_SESSION['admin']['id']=$info['admin_id'];
            $_SESSION['admin']['name']=$info['admin_name'];
            return true;
        }else{
            return false;
        }
    }

    /**
     * 修改最后一次登录的时间
     * @param $user_id
     */
    function changeLastLoginTime($user_id){
        $this->changeOneFiled('last_login_time',date('Y-m-d H:i:s'),$user_id);
    }
    static function checkLogin(){
        return isset($_SESSION['admin']['id'])&&$_SESSION['admin']['id'];
    }
    function loginOut(){
        unset($_SESSION['admin']);
        return true;
    }
}