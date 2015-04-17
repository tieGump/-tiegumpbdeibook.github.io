<?php

/** 检查登录
 * Class Login
 * @author tie.Gump
 */
class Login{
    function checkLogin($in_password,$save_password){
        $tmp=explode(':',$save_password);
        $key=$tmp[1];
        return $this->createPassword($in_password,$key)==$save_password;
    }
    function createPassword($password,$key=''){
        $key=$key?$key:$this->createRand();
        return md5(md5($password.$key).$key).':'.$key;
    }
    function createRand(){
        $i=0;
        $return='';
        while($i<3){
            $k=rand(1,3);
            if($k==1){
                $return.=rand(1,9);
            }else if($k==2){
                $return.=chr(rand(65,90));
            }else if($k==3){
                $return.=chr(rand(97,122));
            }
            $i++;

        }
        return $return;
    }
}