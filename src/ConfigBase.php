<?php
/**
 * Created by PhpStorm.
 * User: WangTieJun
 * Date: 2015/4/27
 * Time: 17:41
 */

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
            $str_sql='U';
        }
    }
}