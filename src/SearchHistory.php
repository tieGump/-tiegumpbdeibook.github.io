<?php

class SearchHistory extends Mode{
    function __construct(){
        $this->_table='bdei_search_history';
        $this->_table_id='search_id';
        parent::__construct();
    }
    function addSearch($search_info,$type){
        $data['search']=$search_info;
        $data['search_type']=$type;
        $data['search_time']=date('Y-m-d H:i:s');
        $data['ip']=get_client_ip();
        $data['user_id']=isset($_SESSION['user']['id'])?$_SESSION['user']['id']:'';
        return $this->addOne($data);
    }
    function getList($user_id='',$search_value=''){
        $where='';
        if($user_id){
            $where.=' AND user_id='.(int)$user_id;
        }
        if($search_value){
            $where.=' AND search_value LIKE "%'.$search_value.'%"';
        }
        $str_sql='SELECT '.$this->_fields.' FROM '.$this->_table.' WHERE '.$where.' ORDER BY '.$this->_table_id.' DESC';
        return $this->getPageList($str_sql);
    }
}