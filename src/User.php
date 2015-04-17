<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-4-9
 * Time: 上午11:17
 */

/**用户
 * Class User
 * @author tie.Gump
 */
class User extends Mode{
    function __construct(){
        $this->_table='bdei_user';
        $this->_table_id='user_id';
        parent::__construct();
    }

    /**
     * 注册
     */
    function register($post){
        $login=new Login();
        $data['user_name']=$post['user_name'];
        $data['user_pwd']=$login->createPassword($post['user_pwd']);
        $data['user_nick']=$post['user_nick'];
        $data['user_sex']=$post['user_sex'];
        return $this->addOne($data);
    }
    function getList(){
        $str_sql='SELECT '.$this->_filed.' FROM '.$this->_table;
        return $this->getPageList($str_sql);
    }
    function changeInfo($post,$id){

        $data['user_nick']=$post['user_nick'];
        if($post['user_pwd']&&$post['user_pwd']==$post['user_pwd_re']){
            $login=new Login();
            $data['user_pwd']=$login->createPassword($post['user_pwd']);
        }
        $data['user_sex']=$post['user_sex'];
        return $this->changeOne($data,$id);
    }
    function getInfoByName($name){
        return $this->getOne('user_name="'.$name.'"');
    }
    function login($user_name,$password){
        $info=$this->getInfoByName($user_name);
        $login=new Login;
        return $login->checkLogin($password,$info['user_pwd']);
    }
    function checkLogin(){
        return isset($_SESSION['user']['id'])&&$_SESSION['user']['id'];
    }
}