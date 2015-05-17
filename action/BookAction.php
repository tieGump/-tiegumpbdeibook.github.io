<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-5-1
 * Time: 下午7:43
 */

class BookAction extends Action{
    /**
     * 搜索
     */
    function searchAction(){
        $search_history=new SearchHistory();
        $search_history->addSearch($_GET['keyword'],$_GET['findtype']);
        $book=new Book();
        $this->az_type=$book->getAZtype();
        $this->book_list=$tmp=$book->indexSearch($_GET['findtype'],$_GET['keyword']);
        $this->search_top_list=$search_history->getTopSearch();
        $this->_tpl='book_search_list.html';
    }
    function azListAction(){
        $book=new Book();
        $this->book_list=$book->getAZlist($_GET['az']);
        $this->book_host_list=$tmp=$book->getAZlist($_GET['az'],1,20);
        $this->az_type=$book->getAZtype();
        $this->_tpl='book_az_list.html';
    }

    /**
     * 分类搜索
     */
    function classSearchAction(){

    }

    /**
     * 视频中心
     */
    function videoAction(){
        $book=new Book();

        $this->video_type=$tmp=$book->getVideoType();
        $this->video_type_name=$tmp[$_GET['book_type']];
        $this->book_list=$book->getIndexList(array('category_id'=>3,'category_extend_id'=>(int)$_GET['book_type']));
        $this->_tpl='book_video_list.html';
    }

    /**
     * 有声图书
     */
    function soundAction(){
        $book=new Book();
        $this->sound_type=$tmp=$book->getSoundType();
        $this->sound_type_name=$tmp[$_GET['book_type']];
        $this->book_list=$book->getIndexList(array('category_id'=>2,'category_extend_id'=>(int)$_GET['book_type']));
        $this->_tpl='book_sound_list.html';
    }

    /**
     * 书本详细
     */
    function InfoAction(){
        $book_id=(int)$_GET['book_id'];
        if(!$book_id)
            die('请输入book_id！！！');
        $book=new Book();
        $book->addOneRead($book_id);
        $read_history=new ReadHistory();
        $read_history->addRead($book_id);
        $book_info=$book->getOneInfo($book_id);
        $book_list=$book->getIndexList(array('category_id'=>$book_info['category_id'],'book_classification_word'=>$book_info['book_classification_word']),' read_number DESC',20);
        $reviews=new Reviews();
        $this->review_list=$reviews->getBookList($book_id);
        $this->book_info=$book_info;
        $this->book_list=$book_list;
        $this->_tpl='book_info.html';
    }
    function addReviewAction(){
        $reviews=new Reviews();
        $book_id=(int)$_POST['book_id'];
        if(!$_SESSION['user']['id']){
            echo <<<EOF
           <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
</head>

<body>
<script type="text/javascript" charset="utf-8">
alert('请先登录！！！');
location.href='/book/info/book_id/$book_id';
</script>
</body>
</html>
EOF;
        }elseif($_POST['reviews_content']){
            $reviews->addReview($_POST['reviews_content'],$book_id);
            echo <<<EOF
            <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
</head>

<body>
<script type="text/javascript" charset="utf-8">
alert('评论成功！！！');
location.href='/book/info/book_id/$book_id';
</script>
</body>
</html>
EOF;
        }else{
            redirect('/book/info/book_id/'.$book_id);
        }

    }
    function readAction(){
        $book_id=(int)$_GET['book_id'];
        $book=new Book();
        $book_info=$book->getOneInfo($book_id);
//        print_r($book_info);
        $file=UPLOAD_DIR.$book_info['save_place'];
        $file=iconv('UTF-8','GBK',$file);
        $file_name=end(explode('/',$book_info['save_place']));
//        echo $file;
        if($book_info['book_type']=='PDF'){

            header('Content-type: application/pdf');
            header('filename='.($file_name));
            readfile($file);
        }else{
//            $file=UPLOAD_DIR_TRUE.$book_info['save_place'];
            if(is_file($file)) {
                header("Content-type: text/html;charset=utf-8");
                header("Content-Type: application/force-download");
                header("Content-Disposition: attachment; filename=".($file_name));
                readfile($file);
                exit;
            }else{
                echo '<META content="text/html; charset=utf-8" http-equiv=Content-Type>';
                echo "文件不存在！";
                exit;
            }
        }
    }
    function videoInfoAction(){
        $book_id=(int)$_GET['book_id'];
        $book=new Book();
        $this->book_info=$book_info=$book->getOneInfo($book_id);
        $this->movie_dir=iconv('UTF-8','GBK',UPLOAD_DIR.$book_info['save_place']);
        $this->_tpl='book_video_show.html';
    }
    function soundInfoAction(){

        $book_id=(int)$_GET['book_id'];
        $book=new Book();
        $this->book_info=$book_info=$book->getOneInfo($book_id);
//        print_r($book_info);
        $this->book_list=$book->getIndexList(array('category_id'=>$book_info['category_id'],'category_extend_id'=>$book_info['category_extend_id']),' read_number DESC',20);
        $this->movie_dir=UPLOAD_DIR.$book_info['save_place'];
//        $this->movie_dir=UPLOAD_DIR.$book_info['save_place'];
        $this->_tpl='book_sound_show.html';
    }
    function addBookMarkAction(){
        $book_id=(int)$_GET['book_id'];
        if(!$_SESSION['user']['id']){
            echo <<<EOF
            <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
</head>

<body>
<script type="text/javascript" charset="utf-8">
alert('请先登录！！！');
location.href='/book/info/book_id/$book_id';
</script>
</body>
</html>
EOF;
        }else{
            $book_mark=new BookMark();
            if($book_mark->addMark($_SESSION['user']['id'],$book_id)){
                echo <<<EOF
            <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
</head>

<body>
<script type="text/javascript" charset="utf-8">
alert('书签加入成功！！！');
location.href='/book/info/book_id/$book_id';
</script>
</body>
</html>
EOF;
            }else{
                echo <<<EOF
            <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
</head>

<body>
<script type="text/javascript" charset="utf-8">
alert('书签加入失败，请检查是否已加入了您的书签！！！');
location.href='/book/info/book_id/$book_id';
</script>
</body>
</html>
EOF;
            }

        }

    }
} 