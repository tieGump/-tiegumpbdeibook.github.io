<?php

/**
 * 搜索历史
 * Class SearchHistoryAction
 * @author tie.Gump
 */
class SearchHistoryAction extends Action{
    function IndexAction(){
        $search=new SearchHistory();
        $this->search_list=$search->getList('',$_GET['search_value'],$_GET['user_name']);
        $this->_tpl='search_list.html';
    }
    function deleteAction(){
        $search_id=$_GET['search_id'];
        $search=new SearchHistory();
        $search->delete($search_id);
        $this->IndexAction();
    }
}