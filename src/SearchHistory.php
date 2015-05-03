<?php

class SearchHistory extends Mode{
    function __construct(){
        $this->_table='bdei_search_history';
        $this->_table_id='search_id';
        parent::__construct();
    }
    function addSearch($search_info,$type){
        if(!$search_info)
            return;
        $data['search_value']=$search_info;
        $data['search_type']=$type;
        $data['search_time']=date('Y-m-d H:i:s');
        $data['ip']=get_client_ip();
        $data['user_id']=isset($_SESSION['user']['id'])?$_SESSION['user']['id']:0;
        return $this->addOne($data);
    }
    function getList($user_id='',$search_value='',$user_name=''){
        $where='1';
        if($user_id){
            $where.=' AND user_id='.(int)$user_id;
        }
        if($search_value){
            $where.=' AND search_value LIKE "%'.$search_value.'%"';
        }
        if(!$user_id&&$user_name){
            $user=new User();
            if($user_id_arr=$user->resolveUserName($user_name)){
                $where.=' AND user_id IN ('.join(',',$user_id_arr).')';
            }
        }
        $str_sql='SELECT '.$this->_fields.' FROM '.$this->_table.' WHERE '.$where.' ORDER BY '.$this->_table_id.' DESC';
        return $this->getPageList($str_sql);
    }
    function getTopSearch(){
        $str_sql='SELECT search_value,COUNT(search_id) as search_total,search_type FROM bdei_search_history GROUP BY search_value ORDER BY search_total DESC LIMIT 20';
        return $this->_db->doSelect($str_sql);
    }
}