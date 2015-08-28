<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-4-2
 * Time: 下午3:21
 */

/**首页的动作
 * Class Index
 * @author tie.Gump
 */
class IndexAction extends Action{
    function __construct(){
        parent::__construct();
    }
    /**
     * 首页显示
     */
    function indexAction(){
        $book=new Book();
        $this->az_type=$book->getAZtype();

        $this->sound_type=$sound_type=$book->getSoundType();
        foreach($sound_type as $key=>$value){
            if($key<=4)
            $sound_type1[$key]=$value;
            else
                $sound_type2[$key]=$value;
        }
        $this->sound_type1=$sound_type1;
        $this->sound_type2=$sound_type2;
        $this->sound_type_img=$book->getSoundTypeImage();
        $this->video_type=$video=$book->getVideoType();
        foreach($video as $key=>$value){
            if($key<=4)
                $video_type1[$key]=$value;
            else
                $video_type2[$key]=$value;
        }
        $this->video_type1=$video_type1;
        $this->video_type2=$video_type2;
        $this->video_type_img=$book->getVideoTypeImage();
        //最新加入
        $this->new_in_book=$book->getIndexList(array(),'book_id DESC',7);
        //阅读排行
        $this->read_book_top=$book->getIndexList(array(),'read_number DESC',50);
        //统计
        $this->book_total=$book->getTotal();
        //友情链接
        $links=new Links();
        $this->links_list=$links->getList(15);
        //软件下载
        $soft=new DownloadSoft();
        $this->soft_list=$soft->getList(5);

        //首页推荐
        $this->index_recommend=$book->getIndexRecommend();
//        print_r($this->index_recommend);


        $this->upload_dir=UPLOAD_DIR;
        $this->_tpl='index.html';
    }
}