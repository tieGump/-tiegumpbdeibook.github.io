<?php

class Reviews extends Mode{
    function __construct(){
        $this->_table='bdei_review';
        $this->_table_id='review_id';
        parent::__construct();
    }
    function addReview($content,$book_id){
        $data['review_content']=$content;
        $data['user_id']=$_SESSION['user']['id'];
        $data['review_time']=date('Y-m-d H:i:s');
        $data['user_name']=$_SESSION['user']['name'];
        $data['book_id']=$book_id;
        $data['ip']=get_client_ip();
        return $this->addOne($data);
    }
    function getUserList($user_id){
        $info=$this->getList('',$user_id);
        return $info;
    }
    function getBookList($book_id){
        $info=$this->getList('',0,$book_id);
        return $info;
    }
    function getList($content='',$user_id='',$book_id=''){
        $where='1';
        if($content){
            $where.=' AND review_content LIKE "%'.$content.'%"';
        }
        if($user_id){
            $where.=' AND user_id = '.$user_id;
        }

        if($book_id){
            $where.=' AND book_id ='.$book_id;
        }
        $str_sql='SELECT '.$this->_fields.' FROM '.$this->_table.' WHERE '.$where.' ORDER BY '.$this->_table_id.' DESC';
        return $this->getPageList($str_sql);
    }
}