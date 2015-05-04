<?php
/**
 *
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/4/9
 * Time: 11:10
 */

class ReadHistory extends Mode{
    private $_user_id;
    function __construct(){
        $this->_user_id=$_SESSION['user']['id'];
        $this->_table='bdei_user_read_history';
        $this->_table_id='history_id';
        parent::__construct();
    }

    /**
     * 增加一个阅读
     * @param $book_id
     * @return mixed
     */
    function addRead($book_id){
        $data['book_id']=$book_id;
        $data['user_id']=$this->_user_id;
        $data['add_time']=date('Y-m-d H:i:s');
        return $this->addOne($data);
    }
    function getUserList(){
        $str_sql='SELECT b.book_id,b.book_name,b.book_author,urh.add_time FROM bdei_book b,bdei_user_read_history urh WHERE b.book_id=urh.book_id AND urh.user_id='.$this->_user_id;
        return $this->getPageList($str_sql);
        return $this->_db->doSelect($str_sql);
    }
    function drop($id=''){
        if($id){
            return $this->delete($id);
        }
        $this->delete('user_id='.$this->_user_id);

    }
}