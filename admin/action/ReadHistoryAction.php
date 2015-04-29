<?php

class ReadHistoryAction extends Action{
    function indexAction(){
        $book=new Book();
        $this->book_list=$book->getIndexList(array(),'read_number DESC');
        $this->_tpl='read_history_list.html';
    }
}