<?php
/**
 *
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/4/2
 * Time: 17:01
 */

class Book extends Mode{
    function __construct(){
        $this->_table='bdei_book';
        $this->_table_id='book_id';
        parent::__construct();
    }

    /**
     * 新增或者更新
     * @param $post
     * @param string $id
     * @return mixed
     */
    function addAndUpdate($post,$id=''){
        $data['book_name']=$post['book_name'];
        $data['book_author']=$post['book_author'];
        $data['book_press']=$post['book_press'];
        $data['book_isbn']=$post['book_name'];
//        $data['save_place']=$post['save_place'];保存位置
//        $data['book_cover']=$post['book_cover'];封面
        $data['status']=$post['status'];
        $data['book_classification']=$post['book_classification'];
        $data['book_classification_word']=$post['book_classification_word'];
        $data['add_time']=date('Y-m-d H:i:s');
        $data['category_id']=$post['category_id'];
        $data['save_place']=$post['save_place'];
        $data['status']=$post['status'];
        $data_desc['book_keyword']=$post['book_keyword'];
        $data_desc['book_key_words']=$post['book_key_words'];
        $data_desc['book_desc']=$post['book_desc'];
        if($id){
            $this->changeOne($data,(int)$id);
            $where = $this->createWhere($id);
            $this->_db->changeOne('bdei_book_extend',$data_desc,$where);
        }else{
            $id=$this->addOne($data);
            $data_desc['book_id']=$id;
            $this->_db->addOne('bdei_book_extend',$data_desc);
        }
        return $id;
    }

    /**
     * 增加一个阅读
     * @param $book_id
     */
    function addOneRead($book_id){
        $this->changeOneFiled('read_number=','read_number+1',$book_id);
    }

    /**
     * 获取列表
     * @param array $post
     * @param string $order_by
     * @param string $limit
     * @return mixed
     */
    function getIndexList($post=array(),$order_by='',$limit=''){
        $where=$this->createWhere($post);
        $str_sql='SELECT '.$this->_fields .' FROM '.$this->_table.' WHERE '.$where.$this->createOrderBy($order_by);
        if($limit){
            $str_sql.=' LIMIT '.$limit;
            return $this->_db->doSelect($str_sql);
        }
        return $this->getPageList($str_sql);
    }

    /**
     * 创建获取列表的WHERE 条件
     * @param $info
     * @return string
     */
    function createWhere($info){
        $where='1';
        if(isset($info['book_name'])&&$info['book_name']){
            $where.=' AND book_name LIKE "%'.$info['book_name'].'%"';
        }
        if(isset($info['book_author'])&&$info['book_author']){
            $where .=' AND book_author LIKE "%'.$info['book_author'].'%"';
        }
        if(isset($info['category_id'])&&$info['category_id']){
            $where.=' AND category_id IN ('.$info['category_id'].')';
        }
        if(isset($info['book_isbn'])&&$info['book_isbn']){
            $where.=' AND book_isbn LIKE "%'.$info['book_isbn'].'%"';
        }
        if(isset($info['book_classification'])&&$info['book_classification']){
            $where.=' AND book_classification LIKE "%'.$info['book_classification'].'%"';
        }
        if(isset($info['book_classification_word'])&&$info['book_classification_word']){
            $where.=' AND book_classification_word ="'.$info['book_classification_word'].'"';
        }
        return $where;
    }

    /**
     * 创建排序
     * @param $order_by
     * @return string
     */
    function createOrderBy($order_by){
        if($order_by)
            return $order_by;
        return ' ORDER BY book_id DESC';
    }

    /**
     * 获取前台页面搜索的列表
     * @param $type
     * @param $value
     * @return mixed
     */
    function indexSearch($type,$value){
        switch($type){
            case 'name':
                $data['book_name']=$value;
                break;
            case 'author':
                $data['book_author']=$value;
                break;
            case 'class':
                $data['book_classification']=$value;
                break;
            default : $data['book_name']=$value;
                break;
        }
        return $this->getIndexList($data);
    }

    /**
     * 获取A-Z类型的列表
     * @param $word
     * @return mixed
     */
    function getAZlist($word){
        $info=array('book_classification_word'=>$word);
        return $this->getIndexList($info);
    }

    /**
     * 删除
     * @param $id
     */
    function dropOne($id){
        $this->delete($id);
        $this->_db->delete('bdei_book_extend', 'book_id='.(int)$id);
    }
}