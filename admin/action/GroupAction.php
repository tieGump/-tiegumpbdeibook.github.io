<?php
class GroupAction extends Action{
    function indexAction(){
        $group=new Group();
        $this->group_list=$group->getList(2,$_GET);
        $this->_tpl='group_list.html';
    }
    function permissionAction(){

    }
    function editAction()
    {
        $group=new Group();
        $this->group_info=$group->getOne($_GET['group_id']);
        $this->_tpl='group_edit.html';
    }
    function changePermissionAction(){

    }
    function doEditAction(){

    }
    function deleteAction(){
        $group=new Group();
        $group->delete($_GET['group_id']);
    }
}