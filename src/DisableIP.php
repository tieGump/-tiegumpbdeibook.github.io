<?php

class DisableIP extends Mode{
    function __construct(){
        $this->_table='bdei_disable_ip';
        $this->_table_id='id';
        parent::__construct();
    }
    function getList(){
        $str_sql='SELECT '.$this->_fields.' FROM '.$this->_table;
        return $this->getPageList($str_sql);
    }
    function getAllList(){
        $str_sql='SELECT '.$this->_fields.' FROM '.$this->_table;
        return $this->_db->doSelect($str_sql);
    }
    function add($post){
        if(!$this->checkIsIpAddress($post['ip_address'])){
            return false;
        }
        $data=$this->createData($post);
        $data['add_time']=date('Y-m-d H:i:s');
        return $this->addOne($data);
    }
    function createData($post){
        $data['ip_address']=$post['ip_address'];
        $tmp=explode('.',$post['ip_address']);
        $data['one']=(int)$tmp[0];
        $data['two']=(int)$tmp[1];
        $data['three']=(int)$tmp[2];
        if(!$post['start']&&!$post['end']){
            $data['start']=(int)$tmp[3];
            $data['end']=(int)$tmp[3];
        }else{
            $data['start']=$post['start']?(int)$post['start']:1;
            $data['end']=$post['end']?(int)$post['end']:'255';
        }

        return $data;
    }
    function change($post,$id){
        if(!$this->checkIsIpAddress($post['ip_address'])){
            return false;
        }
        $data=$this->createData($post);
        $this->changeOne($data,$id);
        return true;
    }
    function checkIsIpAddress($ip_address){
        $tmp=explode('.',$ip_address);
        if(count($tmp)!=4)
            return false;
        return true;

    }
    function check(){
        if($_SESSION['open_ip']){
            return true;
        }
        if(isset($_SESSION['open_ip'])&&$_SESSION['open_ip']==0){
            return false;
        }
        $config=new ConfigBase();
        if($config->getOneValue('disable_ip')){
            $ip=get_client_ip();
            $tmp=explode('.',$ip);
            $str_sql='SELECT '.$this->_fields.' FROM '.$this->_table." WHERE one=$tmp[0] AND two=$tmp[1] AND three=$tmp[3]";
            $info=$this->_db->doSelect($str_sql);
            foreach($info as $value){
                if($tmp[3]>=$value['start']&&$tmp[3]<=$value['end']){
                    $_SESSION['open_ip']=0;
                    return false;
                }
            }
            return true;
        }else{
            $_SESSION['open_ip']=1;
            return true;
        }
    }
}