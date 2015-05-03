<?php

class BookAction extends Action{
    function indexAction(){
        $book=new Book();
        $this->book_type=$book->getBookType();
        $this->_data['book_list']=$book->getIndexList($_GET);
        $this->_tpl='book_list.html';
    }
    function addAction(){
        $this->upload_dir = UPLOAD_DIR;
        $this->book_type=Book::getBookType();
        $this->book_video_type=Book::getVideoType();
        $this->book_sound_type=Book::getSoundType();
        $this->_tpl='book_add.html';
    }
    function doAddAction(){
        $file=new File();
        if($book_cover=$file->uploadFile($_FILES['book_cover_file'],'book_cover/')){
            $_POST['book_cover']=$book_cover;
        }
        if($book_file=$file->uploadFile($_FILES['book_file'],'book/')){
            $_POST['save_place']=$book_file;
        }
        $book_id=(int)$_POST['book_id'];
        $book=new Book();
        $book->addAndUpdate($_POST,$book_id);
        redirect('/admin/book');

    }
    function editAction(){
        $book=new Book();
        $book_id=(int)$_GET['book_id'];
        $this->book_info=$tmp=$book->getOneInfo($book_id);
        $this->upload_dir = UPLOAD_DIR;
        $this->book_type=Book::getBookType();
        $this->book_video_type=Book::getVideoType();
        $this->book_sound_type=Book::getSoundType();
        $this->_tpl='book_add.html';
    }
    function doEditAction(){

    }
    function deleteAction(){
        $book=new Book();
        $book->dropOne((int)$_GET['book_id']);
        $this->indexAction();
    }
} 