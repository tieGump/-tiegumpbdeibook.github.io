<?php

class UserAction extends Action{
    private $_user_id;
    function __construct(){

        parent::__construct();
        $this->_user_id=$_SESSION['user']['id'];
        if(!$_SESSION['user']['id']){
            echo <<<EOF
<script>
alert('您还没有登录！请先登录！！！');
location.href='/';
</script>
EOF;

        }
    }
    function indexAction(){
        $user=new User();
        $this->user_info=$tmp=$user->getOne($this->_user_id);
        $this->_tpl='user_base_info.html';
    }
    function updateUserInfoAction(){
        $user=new User();
        $user->register($_POST,$this->_user_id);
        redirect('/user');
    }
    function BookmarkAction(){
        $bookmark=new BookMark();
        $this->bookmark_list=$bookmark->getList($this->_user_id);
        $this->_tpl='user_bookmark.html';
    }
    function deleteBookmarkAction(){
        $bookmark=new BookMark();
        $bookmark->delete($_GET['bookmark_id']);
        redirect('/user/bookmark');
    }
    function reviewsAction(){
        $reviews=new Reviews();
        $this->review_list=$reviews->getUserList($this->_user_id);
        $this->_tpl='user_reviews.html';
    }
    function readHistoryAction(){
        $read_history=new ReadHistory();
        $this->read_history=$read_history->getUserList();
        $this->_tpl='user_read_history.html';
    }
    function searchHistoryAction(){
        $search_history=new SearchHistory();
        $this->search_history_list=$search_history->getList($this->_user_id);
        $this->_tpl='user_search_history.html';
    }
    function deleteSearchHistoryAction(){
        $sh=new SearchHistory();
        $sh->delete((int)$_GET['s_h_id']);
        redirect('/user/searchHistory');
    }
}