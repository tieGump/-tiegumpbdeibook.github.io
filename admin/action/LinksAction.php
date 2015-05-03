<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-5-1
 * Time: 下午9:17
 */

class LinksAction extends Action{
    function indexAction(){
        $links=new Links();
        $this->links_list=$links->getList();
        $this->_tpl='links_list.html';
    }
    function addAction(){
        $this->_tpl='links_add.html';
    }
    function editAction(){
        $links=new Links();
        $this->one_info=$links->getOne($_GET['links_id']);
        $this->addAction();
    }
    function doChangeAction(){
        $links=new Links();
        $links->input($_POST,$_POST['links_id']);
        redirect('/admin/links');
    }
    function deleteAction(){
        $links_id=(int)$_GET['links_id'];
        $links=new Links();
        $links->delete($links_id);
        redirect('/admin/links');
    }
} 