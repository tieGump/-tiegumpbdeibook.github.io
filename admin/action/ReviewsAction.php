<?php

class ReviewsAction extends Action{
    function IndexAction(){
        $review=new Reviews();
        $this->review_list=$review->getList($_GET['content'],$_GET['user_id'],$_GET['book_id']);
        $this->_tpl='review_list.html';
    }
    function deleteAction(){
        $review_id=(int)$_GET['review_id'];
        $review=new Reviews();
        $review->delete($review_id);
        $this->IndexAction();
    }
}