<?php

class PermissionAction extends Action{
    function IndexAction(){

    }
    function addAction(){
        $permission=new Permission();
        $permission->setDeepMark("&nbsp;&nbsp;");
        $tree_arr=$permission->createDeepTree();
        $this->tree_arr=$tree_arr;
        $this->_tpl='permission_add.html';
    }
    function doAddAction(){
        $permission=new Permission();
        $permission->addPermission($_POST);
        $this->_message->addSession('添加成功！！！','success');
        redirect('/admin/permission/add');
    }
}