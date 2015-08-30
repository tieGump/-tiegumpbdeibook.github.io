<?php

class BookAction extends Action{
    function indexAction(){
        $book=new Book();
        $this->book_type=$book->getBookType();
        if($_GET['search_name'])
        $this->_data['book_list']=$book->indexSearch($_GET['search_type'],$_GET['search_name']);
        else
            $this->_data['book_list']=$book->getIndexList();
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
        $book=new Book();
        $book_type=Book::getBookType();
        $book_video_type=Book::getVideoType();
        $book_sound_type=Book::getSoundType();
        $type_dir=$book_type[$_POST['category_id']];
        if($_POST['category_id']==2){
            $type_dir.='/'.$book_video_type[$_POST['category_video_id']];
        }
        if($_POST['category_id']==3){
            $type_dir.='/'.$book_sound_type[$_POST['category_sound_id']];
        }
        if($book_cover=$file->uploadFile($_FILES['book_cover_file'],'book/cover/'.$type_dir.'/')){
            $_POST['book_cover']=$book_cover;
        }
//        print_r($_POST);
//        exit;
        if($book_file=$file->uploadFile($_FILES['book_file'],'book/file/'.$type_dir.'/')){
            $_POST['save_place']=$book_file;
        }
        if($text_info=$file->uploadFile($_FILES['text_info'],'book/text/'.$type_dir.'/')){
            $_POST['text_info']=$text_info;
        }
        $book_id=(int)$_POST['book_id'];

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
        redirect('/admin/book');
    }
    function setIndexAction(){
        $book_id=(int)$_GET['book_id'];
        $book=new Book();
        $book->setIndexShow($book_id);
        redirect('/admin/book');
    }
} 