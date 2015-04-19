<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-4-19
 * Time: 上午10:34
 */

class BookAction extends Action{
    function indexAction(){
        $book=new Book();
        $this->_data['book_list']=$book->getIndexList($_GET);
        $this->_tpl='book_list.html';
    }
    function addAction(){

    }
    function doAddAction(){

    }
    function editAction(){

    }
    function doEditAction(){

    }
    function deleteAction(){
        $book=new Book();
        $book->delete($_GET['book_id']);
        $this->indexAction();
    }
} 