<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-5-1
 * Time: 下午9:17
 */

class SoftAction extends Action {
    function indexAction(){
        $soft=new DownloadSoft();
        $this->soft_list=$soft->getList();
        $this->_tpl='soft_list.html';
    }
    function addAction(){
        $this->upload_url=UPLOAD_DIR.'soft/';
        $this->_tpl='soft_add.html';
    }
    function editAction(){
        $soft=new DownloadSoft();
        $this->one_info=$soft->getOne($_GET['soft_id']);
        $this->addAction();
    }
    function doChangeAction(){
        if($_FILES){
            $file=new File();
            if($_FILES['soft_cover']&&$_FILES['soft_cover']['error']==0){
                $cover_name=$file->uploadFile($_FILES['soft_cover'],'upload/soft/cover');
                $_POST['soft_pic']=$cover_name;
            }
        }
        if($_POST['soft_name']){
            $soft=new DownloadSoft();
            $soft->input($_POST,$_POST['soft_id']);

        }else{
            $this->_message->addSession('新增失败！！！软件名称不能为空');
        }
            redirect('/admin/soft');

    }
    function deleteAction(){
        $soft=new DownloadSoft();
        $soft->delete($_GET['soft_id']);
        redirect('/admin/soft');
    }
} 