<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-5-1
 * Time: 下午7:43
 */

class BookAction extends Action{
    /**
     * 搜索
     */
    function searchAction(){
        $search_history=new SearchHistory();
        $search_history->addSearch($_GET['keyword'],$_GET['findtype']);
        $book=new Book();
        $this->az_type=$book->getAZtype();
        $this->book_list=$tmp=$book->indexSearch($_GET['findtype'],$_GET['keyword']);
        $this->search_top_list=$search_history->getTopSearch();
        $this->_tpl='book_search_list.html';
    }
    function azListAction(){
        $book=new Book();
        $this->book_list=$book->getAZlist($_GET['az']);
        $this->book_host_list=$tmp=$book->getAZlist($_GET['az'],1,20);
        $this->az_type=$book->getAZtype();
        $this->_tpl='book_az_list.html';
    }

    /**
     * 分类搜索
     */
    function classSearchAction(){

    }

    /**
     * 视频中心
     */
    function videoAction(){
        $book=new Book();

        $this->video_type=$tmp=$book->getVideoType();
        $this->video_type_name=$tmp[$_GET['book_type']];
        $this->book_list=$book->getIndexList(array('category_id'=>3,'category_extend_id'=>(int)$_GET['book_type']));
        $this->_tpl='book_video_list.html';
    }

    /**
     * 有声图书
     */
    function soundAction(){
        $book=new Book();
        $this->sound_type=$tmp=$book->getSoundType();
        $this->sound_type_name=$tmp[$_GET['book_type']];
        $this->book_list=$book->getIndexList(array('category_id'=>2,'category_extend_id'=>(int)$_GET['book_type']));
        $this->_tpl='book_sound_list.html';
    }

    /**
     * 书本详细
     */
    function InfoAction(){
        $book_id=(int)$_GET['book_id'];
        if(!$book_id)
            die('请输入book_id！！！');
        $book=new Book();
        $book_info=$book->getOneInfo($book_id);
        $book_list=$book->getIndexList(array('category_id'=>$book_info['category_id'],'category_extend_id'=>$book_info['category_extend_id']),' read_number DESC',20);
        $this->book_info=$book_info;
        $this->book_list=$book_list;
        $this->_tpl='book_info.html';
    }
    function videoInfoAction(){
        $this->_tpl='book_video_info.html';
    }
    function soundInfoAction(){
        $this->_tpl='book_sound_info.html';
    }
} 