<?php
class GroupAction extends Action{
    function indexAction(){
        $group=new Group();
        $this->group_list=$list=$group->getList(2,$_GET);
        $this->_tpl='group_list.html';
    }
    function editAction()
    {
        $group_id=(int)$_GET['group_id'];
        $group=new Group();
        $this->group_info=$group->getOne($group_id);
        $permission=new Permission();
        $permission->setDeepMark("&nbsp;&nbsp;&nbsp;&nbsp;");
        $this->group_permission=$tmp=Arrays::downArray($permission->getGroupPermission($group_id),'permission_id');
        $this->permission_list=$permission->createDeepTree();
        $this->_tpl='group_edit.html';
    }
    function addAction(){
        $permission=new Permission();
        $permission->setDeepMark("&nbsp;&nbsp;&nbsp;&nbsp;");
        $this->permission_list=$permission->createDeepTree();
        $this->_tpl='group_add.html';
    }
    function doAddAction(){
        $group=new Group();
        $group_id=$group->addGroup($_POST['group_name']);
        $permission=new Permission();
        $permission->editGroupPermission($group_id,$_POST['permission']);
        $this->_message->addSession('添加成功！！！','success');
        redirect('/admin/group/');
    }
    function doEditAction(){
        $group_id=(int)$_POST['group_id'];
        $group=new Group();
        $group->changeOne(array('group_name'=>$_POST['group_name']),$group_id);
        $permission=new Permission();
        $permission->editGroupPermission($group_id,$_POST['permission']);
        $this->_message->addSession('修改成功！！！','success');
        redirect('/admin/group/edit/group_id/'.$group_id);
    }
    function deleteAction(){
        $group=new Group();
        $group->delete((int)$_GET['group_id']);
        $permission=new Permission();
        $permission->deleteGroupPermission((int)$_GET['group_id']);
    }
}