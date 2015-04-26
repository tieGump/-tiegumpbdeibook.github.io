<?php

/**
 * Class Message
 * @author tie.Gump
 */
class Message {
    private $_message='';
    private $_mark=0;
    function add($message){
        $this->_message.=$message;
    }

    function addSession($message,$type='failure'){
        $arr=array('success'=>'<font color="green">','failure'=>'<font color="red">');
        $_SESSION['message'].=$arr[$type].$message.'</font>';
        $this->_mark=1;
    }
    function checkFirst(){
        return $this->_mark;
    }
    function getMessage(){
        return $this->_message;
    }
    function getSessionMessage(){
        return $_SESSION['message'];
    }
    function clearSessionMessage(){
        unset($_SESSION['message']);
    }
    function clearMessage(){
        $this->_message='';
    }
}