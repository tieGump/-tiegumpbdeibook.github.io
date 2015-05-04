<?php

/**
 * 用户书签管理
 * Class BookMark
 * @author tie.Gump
 */
class BookMark extends Mode{
    function __construct(){
        $this->_table='bdei_bookmark';
        $this->_table_id='bookmark_id';
        parent::__construct();
    }
    function getList($user_id='',$book_id=''){
        $where=' b.book_id=bm.book_id';
        if($user_id){
            $where.=' AND bm.user_id='.$user_id;
        }
        if($book_id){
            $where.=' AND bm.book_id='.$book_id;
        }
        $str_sql='SELECT bm.bookmark_id,b.book_name,b.book_id,bm.add_time,b.book_author FROM bdei_book b , bdei_bookmark bm WHERE  '.$where;
        return $this->getPageList($str_sql,2);
    }
    function addMark($user_id,$book_id){
        $data['user_id']=$user_id;
        $data['book_id']=$book_id;
        $data['add_time']=date('Y-m-d H:i:s');
        return $this->addOne($data);
    }

}