<?php
/**
 *
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/4/3
 * Time: 14:20
 */

class BookType {
    private $_book_type;
    function __construct(){
        $this->_book_type=array(1=>'普通图书',2=>'有声图书',3=>'视频资源');
    }

    /**
     * 获取类型
     * @return mixed
     */
    function getType(){
        return $this->_book_type;
    }

    /**
     * 创建SELECT 的option
     * @return string
     */
    function createOption(){
        $str='';
        foreach($this->_book_type as $k=>$v){
            $str.="<option value='$k'>$v</option>";
        }
        return $str;
    }
}