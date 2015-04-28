<?php

class ConfigBase extends Mode{
    function __construct(){
        $this->_table='bdei_config';
        $this->_table_id='config_id';
        parent::__construct();
    }
    function getList(){
        $str_sql='SELECT '.$this->_fields.' FROM '.$this->_table;
        return $this->_db->doSelect($str_sql);
    }
    function update($post){
        foreach($post as $key=>$value){
            $str_sql='UPDATE '.$this->_table.' SET config_value='.$value.' WHERE config_unique_name="'.$key.'"';
            $this->_db->query($str_sql);
        }
    }
    function getOneValue($name){
        $info=$this->getOne("config_unique_name='$name'");
        return $info['config_value'];
    }
}