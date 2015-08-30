<?php

class Book extends Mode{
    function __construct(){
        $this->_table='bdei_book';
        $this->_table_id='book_id';
        parent::__construct();
    }
    function getOneInfo($book_id){
        $base_info=$this->getOne($book_id);
        $tmp=explode('.',$base_info['save_place']);
        $base_info['book_type']=strtoupper(end($tmp));
        $base_info['book_cover_show']=(substr($base_info['book_cover'],0,7)=='http://'||substr($base_info['book_cover'],0,8)=='https://')?$base_info['book_cover']:($base_info['book_cover']?UPLOAD_DIR.$base_info['book_cover']:'');

        $extend_info=$this->_db->getOne('bdei_book_extend','book_id='.$book_id);
        if(!$extend_info['book_info']&&$base_info['text_info']&&is_file(UPLOAD_DIR.$base_info['text_info'])){
            $extend_info['book_info']=file_get_contents(UPLOAD_DIR.$base_info['text_info']);
        }
//        $extend_info['book_info_page']=floor(strlen($extend_info['book_info'])/360);
        $extend_info=$extend_info?$extend_info:array();
        return array_merge($base_info,$extend_info);
    }
    /**
     * 新增或者更新
     * @param $post
     * @param string $id
     * @return mixed
     */
    function addAndUpdate($post,$id=''){
        $post['book_classification']=trim($post['book_classification']);
        $data['book_name']=$post['book_name'];
        $data['book_author']=$post['book_author'];
        $data['book_press']=$post['book_press'];
        $data['book_isbn']=$post['book_isbn'];
        $data['save_place']=$post['save_place'];//保存位置
        $data['book_cover']=$post['book_cover'];//封面
        $data['text_info']=$post['text_info'];//text
        $data['status']=$post['status'];
        $data['book_classification']=$post['book_classification'];
        $data['book_classification_word']=ucfirst($post['book_classification'][0]);
        $data['add_time']=date('Y-m-d H:i:s');
        $data['category_id']=$post['category_id'];
        if($post['category_id']==2){
            $data['category_extend_id']=$post['category_sound_id'];
        }
        if($post['category_id']==3){
            $data['category_extend_id']=$post['category_video_id'];
        }
        $data['save_place']=$post['save_place'];
        $data['status']=$post['status'];
        $data_desc['book_keyword']=$post['book_keyword'];
        $data_desc['book_key_words']=$post['book_key_words'];
        $data_desc['book_desc']=$post['book_desc'];
        $data_desc['book_catalog_desc']=$post['book_catalog_desc'];
        $data_desc['book_info']=$post['book_info'];
        if($id){
            $this->changeOne($data,(int)$id);
            $where = $this->createWhere($id);
            if($this->_db->getOne('bdei_book_extend','book_id='.$id)){
                $this->_db->changeOne('bdei_book_extend',$data_desc,$where);
            }else{
                $data_desc['book_id']=$id;
                $this->_db->addOne('bdei_book_extend',$data_desc);
            }

        }else{
            $id=$this->addOne($data);
            $data_desc['book_id']=$id;
            $this->_db->addOne('bdei_book_extend',$data_desc);
        }
        return $id;
    }

    /**
     * 增加一个阅读
     * @param $book_id
     */
    function addOneRead($book_id){
        $str_sql='UPDATE '.$this->_table.' SET read_number=read_number+1 WHERE '.$this->_table_id.'='.$book_id;
        $this->_db->query($str_sql);
    }

    /**
     * 获取列表
     * @param array $post
     * @param string $order_by
     * @param string $limit
     * @return mixed
     */
    function getIndexList($post=array(),$order_by='',$limit=''){
        $where=$this->createMyWhere($post);
        $str_sql='SELECT '.$this->_fields .' FROM '.$this->_table.' WHERE '.$where.$this->createOrderBy($order_by);
        if($limit){
            $str_sql.=' LIMIT '.$limit;
            return $this->_db->doSelect($str_sql);
        }
        return $this->getPageList($str_sql);
    }

    /**
     * 创建获取列表的WHERE 条件
     * @param $info
     * @return string
     */
    function createMyWhere($info){

        $where='1';
        if(isset($info['book_name'])&&$info['book_name']){
            $where.=' AND book_name LIKE "%'.$info['book_name'].'%"';
        }
        if(isset($info['book_author'])&&$info['book_author']){
            $where .=' AND book_author LIKE "%'.$info['book_author'].'%"';
        }
        if(isset($info['category_id'])&&$info['category_id']){
            $where.=' AND category_id IN ('.$info['category_id'].')';
        }
        if(isset($info['book_isbn'])&&$info['book_isbn']){
            $where.=' AND book_isbn LIKE "%'.$info['book_isbn'].'%"';
        }
        if(isset($info['book_classification'])&&$info['book_classification']){
            $where.=' AND book_classification LIKE "%'.$info['book_classification'].'%"';
        }
        if(isset($info['book_classification_word'])&&$info['book_classification_word']){
            $where.=' AND book_classification_word ="'.$info['book_classification_word'].'"';
        }
        if(isset($info['book_id'])&&$info['book_id']){
            $where.=' AND book_id IN ('.$info['book_id'].')';
        }
        if(isset($info['category_extend_id'])&&$info['category_extend_id']){
            $where.=' AND category_extend_id ='.$info['category_extend_id'];
        }
        if(isset($info['index_show'])){
            $where.=' AND index_show = '.$info['index_show'];
        }
        return $where;
    }

    /**
     * 创建排序
     * @param $order_by
     * @return string
     */
    function createOrderBy($order_by){
        if($order_by)
            return ' ORDER BY '.$order_by;
        return ' ORDER BY book_id DESC';
    }

    /**
     * 获取前台页面搜索的列表
     * @param $type
     * @param $value
     * @return mixed
     */
    function indexSearch($type,$value){
        switch($type){
            case '1':
                $data['book_name']=$value;
                break;
            case '2':
                $data['book_author']=$value;
                break;
            case '3':
                $data['book_classification']=$value;
                break;
            case '4':
                $str_sql='SELECT book_id FROM bdei_book_extend WHERE book_key_words LIKE "%'.$value.'%"';
                $info=$this->_db->doSelect($str_sql);
                $info=Arrays::downArray($info,'book_id');
                if($info)
                $data['book_id']=join(',',$info);
                break;
            case '5':
                $str_sql='SELECT book_id FROM bdei_book_extend WHERE book_keyword LIKE "%'.$value.'%"';
                $info=$this->_db->doSelect($str_sql);
                $info=Arrays::downArray($info,'book_id');
                if($info)
                    $data['book_id']=join(',',$info);
                break;
            default : $data['book_name']=$value;
                break;
        }
        return $this->getIndexList($data);
    }

    /**
     * 获取A-Z类型的列表
     * @param $word
     * @return mixed
     */
    function getAZlist($word,$hot='',$limit=''){
        $info=array();
        if($word)
        $info=array('book_classification_word'=>$word);
        $info['category_id']=1;
        $order_by='';
        if($hot){
            $order_by=' read_number DESC';
        }
        return $this->getIndexList($info,$order_by,$limit);
    }
    function getNameById($id){
        $info=$this->getOne($id);
        return $info['book_name'];
    }

    /**
     * 删除
     * @param $id
     * @return int
     */
    function dropOne($id){
        $this->delete($id);
        $this->_db->delete('bdei_book_extend', 'book_id='.(int)$id);
        return $this->_db->getQueryNumber();
    }
    static function getSearchType(){
        return array(1=>'书籍名称',2=>'作者',3=>'分类号',4=>'主题词','5'=>'关键字');
    }
    static function getBookType(){
        return array(1=>'普通图书',2=>'有声资源',3=>'视频资源');
    }

    /**
     * 有声资源类型
     */
    static function getSoundType(){
        return array(1=>'古典诗词曲赋',2=>'中国历史典故',3=>'中国古典文学',4=>'中国近代美文',5=>'世界文学大系',6=>'中外科幻文化',7=>'中外经典童话',8=>'中外侦探小说');
    }
    static function getSoundTypeImage(){
        return array(1=>'1(1).jpg',2=>'2(1).jpg',3=>'3(1).jpg',4=>'4(1).jpg',5=>'5(1).jpg',6=>'6(1).jpg',7=>'7(1).jpg',8=>'8(1).jpg');
    }
    static function getVideoTypeImage(){
        return array(1=>'1(2).jpg',2=>'2(2).jpg',3=>'3(2).jpg',4=>'4(2).jpg',5=>'5(2).jpg',6=>'6(2).jpg',7=>'7(2).jpg',8=>'8(2).jpg');
    }

    /**
     * 视频资源类型
     */
    static function getVideoType(){
        return array(1=>'科技探索篇',2=>'经典战争篇',3=>'成语故事篇',4=>'视频教程篇',5=>'教学资源篇',6=>'经典名著篇',7=>'电视电影篇',8=>'卡通动漫篇');
    }
    function getAZtype(){
        $data['A']='马恩列毛邓';
        $data['B']='哲学、宗教 ';
        $data['C']='社会科学总论';
        $data['D']='政治、法律';
        $data['E']='军事';
        $data['F']='经济';
        $data['G']='文教、科学、体育 ';
        $data['H']='语言、文字';
        $data['I']='文学 ';
        $data['J']='艺术';
        $data['K']='历史、地理';
        $data['N']='自然科学总论';
        $data['O']='数理科学和化学';
        $data['Q']='生物科学';
        $data['R']='医药、卫生';
        $data['S']='农业科学';
        $data['T']='工业技术';
        $data['U']='交通运输';
        $data['V']='航空、航天';
        $data['X']='环境、安全科学';
        $data['Z']='综合性图书';
        return $data;
    }
    function getTotal(){
        $str_sql='SELECT category_id,COUNT(book_id) as count_total FROM bdei_book GROUP BY category_id';
        $info=$this->_db->doSelect($str_sql);
        $type=$this->getBookType();
        $total=0;
        foreach($info as $value){
            $return[$value['category_id']]=array('total'=>$value['count_total'],'name'=>$type[$value['category_id']]);
            $total+=$value['count_total'];
        }
        $return['total']=$total;
        return $return;
    }
    function setIndexShow($book_id){
        $str_sql='UPDATE bdei_book set index_show=(index_show+1)%2 WHERE book_id='.$book_id;
        return $this->_db->query($str_sql);
    }
    function getIndexRecommend(){
        $tmp=$this->getIndexList(array('index_show'=>1),'',8);
        if($number=count($tmp)<8){
            $tmp=array_merge($tmp,$this->getIndexList(array('index_show'=>0),'book_id',8-$number));
        }
        return $tmp;
    }
    function createBookInfoPage($info,$page=0){
        $page=intval($page);
        $j=ceil(mb_strlen($info)/360);
//        $j=1;
        for($i=1;$i<=$j;$i++){
//            echo $j.'010<br />';
            $tmp[]=$i;
        }
        $info=mb_substr($info,($page-1)*360,360);
        return array('page'=>$tmp,'info'=>$info);
    }
}