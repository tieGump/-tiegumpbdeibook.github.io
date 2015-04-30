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
    function register($post,$id=''){
        $login=new Login();
        $data['user_name']=$post['user_name'];
        $post['user_pwd']?$data['user_pwd']=$login->createPassword($post['user_pwd']):'';
        $data['user_nick']=$post['user_nick'];
        $data['user_sex']=$post['user_sex'];
        $data['question']=$post['question'];
        $data['answer']=$post['answer'];
        if($id){
            $this->changeOne($data,$id);
        }else{
            $id=$this->addOne($data);
        }
        return $id;
    }
    function getList(){
        $str_sql='SELECT '.$this->_fields.' FROM '.$this->_table;
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
        if($name)
        return $this->getOne('user_name="'.$name.'"');
    }
    function login($user_name,$password){
        $info=$this->getInfoByName($user_name);
        $login=new Login;
        if($login->checkLogin($password,$info['user_pwd'])){
            $_SESSION['user']['id']=$info['user_id'];
            $_SESSION['user']['name']=$info['user_name'];
            return true;
        }else{
            return false;
        }
    }
    function checkLogin(){
        return isset($_SESSION['user']['id'])&&$_SESSION['user']['id'];
    }
    function resolveUserName($user_name){
        $info=$this->getInfoByName($user_name);
        if($info)
        return Arrays::downArray($info,'user_id');
    }
}