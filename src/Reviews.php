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
        $str_sql='SELECT b.book_id,b.book_name,b.book_author,r.review_time,r.review_id FROM bdei_book b,bdei_review r WHERE b.book_id=r.book_id AND  r.user_id='.$user_id;
        return $this->getPageList($str_sql);
        return $info;
    }
    function getBookList($book_id){
        $info=$this->getList('',0,$book_id);
        return $info;
    }
    function getList($content='',$user_id='',$book_id='',$user_name=''){
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
        if(!$user_id&&$user_name){
            $user=new User();
            if($user_list=$user->resolveUserName($user_name)){
                $where.='AND user_id IN('.join(',',$user_list).')';
            }
        }
        $str_sql='SELECT '.$this->_fields.' FROM '.$this->_table.' WHERE '.$where.' ORDER BY '.$this->_table_id.' DESC';
        return $this->getPageList($str_sql,2);
    }
}