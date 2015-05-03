<?php

/**分页
 * Class Page
 * @author tie.Gump
 */
class Page{
    private $_str_sql;
    private $_page_num;//每页显示多少行
    private $_page_cout;//总共有多少页
    private $_page,$_row_num;
    private $_str_len;//下方页码显示多少个
    private $_db;
    function __construct($str_sql,$page,$page_num=40,$str_len=11){
        $this->_db=DB::getDB();
        $this->_str_sql=$str_sql;
        $this->_page_num=(int)$page_num;
        $this->_page=(int)$page;
        $this->_str_len=(int)$str_len;
        $this->getCount();
        $this->checkPage();

    }

    /**获取处理完之后的信息
     * @return mixed
     */
    public function get(){
        $str_sql=$this->_str_sql.' LIMIT '.(($this->_page-1)*$this->_page_num).','.$this->_page_num;
        $return['info']=$this->_db->doSelect($str_sql);
        $return['page']=IS_ADMIN?$this->createArrayList():$this->createIndex();
        return $return;
    }

    /**修改SQL
     * @param $str_sql
     * @return mixed
     */
    private function changeSql($str_sql){
        $arr_tmp=explode('order by',$str_sql);
        return $arr_tmp[0];
    }
    private function getSqlInfo(){
        $str_sql=$this->_str_sql.' LIMIT '.($this->_page-1)*$this->_page_num.','.$this->_page_num;
        return $this->_db->doSelect($str_sql);
    }

    /**
     * 检查page参数
     */
    private function checkPage(){
        if($this->_page<0)
            $this->_page=1;
        if($this->_page>$this->_page_cout)
            $this->_page=$this->_page_cout;
        if($this->_page==0)
            $this->_page=1;
    }

    /**
     * 获取计算后的东西
     */
    private function getCount(){
        $sql=$this->changeSql($this->_str_sql);
        $this->_row_num=$this->_db->getRowNumber($sql);
        $this->_page_cout=ceil($this->_row_num/$this->_page_num);
    }

    /**生成array
     * @return string
     */
    private function createArrayList(){
        $str_return='';
        if($this->_page_cout<2){//返回空
            return;
        }elseif($this->_page_cout>1&&$this->_page_cout<=$this->_str_len){//不存在省略号的结果
            for($i=1;$i<=$this->_page_cout;$i++){
                $str_return.='<li '.($this->_page==$i?'class="active"':'').'>'.($this->_page==$i?$i:'<a href="'.$this->getUrl($i).'">'.$i.'</a>').'</li>';
            }
        }else{//存在省略号的结果
            $arr=$this->countBeginEnd();
            for($i=$arr['begin'];$i<=$arr['end'];$i++){
                $str_return.='<li '.($this->_page==$i?'class="active"':'').'>'.($this->_page==$i?$i:'<a href="'.$this->getUrl($i).'">'.$i.'</a>').'</li>';
            }
            switch ($arr['place']){
                case 'front' :
                    $str_return.='<li><a>...</a></li>';
                    break;
                case 'mid':
                    $str_return='<li><a>...</a></li>'.$str_return.'<li><a>...</a></li>';
                    break;
                case 'behind':
                    $str_return='<li><a>...</a></li>'.$str_return;
                    break;
                default: new Exception('生成页面的分页信息的时候有错');
            }
        }

        //添加前一一页，下一页
        if($this->_page==1)
            $str_return='<li class="previous-off">&lt;&nbsp;前一页</li>'.$str_return;
        else
            $str_return='<li class="previous"><a href="'.$this->getUrl($this->_page-1).'" class="prev">&lt;&nbsp;前一页</a></li>'.$str_return;
        if($this->_page==$this->_page_cout){
            $str_return.='<li class="next-off">后一页&nbsp;&gt;</li>';
        }else{
            $str_return.='<li class="next"><a href="'.$this->getUrl($this->_page+1).'" class="next">后一页&nbsp;&gt;</a></li>';
        }
        return '<ul id="pagination-clean">'.$str_return.'</ul>';
    }
    function createIndex(){
        $str_return=array();
        if($this->_page_cout<2){//返回空
            return;
        }elseif($this->_page_cout>1&&$this->_page_cout<=$this->_str_len){//不存在省略号的结果
            for($i=1;$i<=$this->_page_cout;$i++){
                $str_return[]=array('url'=>$this->getUrl($i),'page'=>$i,'checked'=>$this->_page==$i?true:false);
            }
        }else{//存在省略号的结果
            $arr=$this->countBeginEnd();
            for($i=$arr['begin'];$i<=$arr['end'];$i++){
                $str_return[]=array('url'=>$this->getUrl($i),'page'=>$i,'checked'=>$this->_page==$i?true:false);
            }
            switch ($arr['place']){
                case 'front' :
                    $str_return[]=array('url'=>'','page'=>'','checked'=>'');
                    break;
                case 'mid':
                    $str_return[]=array('url'=>'','page'=>'','checked'=>'');
                    array_unshift($str_return,array('url'=>'','page'=>'','checked'=>''));
                    break;
                case 'behind':
                    array_unshift($str_return,array('url'=>'','page'=>'','checked'=>''));
                    break;
                default: new Exception('生成页面的分页信息的时候有错');
            }
        }

        //添加前一一页，下一页
        if($this->_page==1)
            $return['previous']=array('url'=>'','page'=>'&lt;&nbsp;前一页','checked'=>true);
        else
            $return['previous']=array('url'=>$this->getUrl($this->_page-1),'page'=>'&lt;&nbsp;前一页','checked'=>false);
        if($this->_page==$this->_page_cout){
            $return['next']=array('url'=>'','page'=>'后一页&nbsp;&gt;','checked'=>true);
        }else{
            $return['next']=array('url'=>$this->getUrl($this->_page+1),'page'=>'后一页&nbsp;&gt;','checked'=>false);
        }
        $return['page_begin']=array('url'=>$this->getUrl(1),'page'=>'首页','checked'=>false);
        $return['page_end']=array('url'=>$this->getUrl($this->_page_cout),'page'=>'尾页','checked'=>false);
        $return['page_info']=$str_return;
        $return['page']=$this->_page;
        $return['page_total']=$this->_page_cout;
        $return['row_number']=$this->_row_num;
        $return['page_per_number']=$this->_page_num;
        return $return;
    }

    /**计算开始结束的标记
     * @return array
     */
    private function countBeginEnd(){
        $begin=$end=0;
        $len_half=floor($this->_str_len/2);
        if($this->_page-$len_half<=1){
            $begin=1;
            $end=$this->_str_len;
            $place='front';
        }elseif($this->_page-$len_half>1&&$this->_page+$len_half<$this->_page_cout){
            $begin=$this->_page-$len_half;
            $end=$this->_page+$len_half;
            $place='mid';
        }elseif($this->_page-$len_half>1&&$this->_page+$len_half>=$this->_page_cout){
            $begin=$this->_page-$len_half;
            $end=$this->_page_cout;
            $place='behind';
        }
        return array('begin'=>$begin,'end'=>$end,'place'=>$place);
    }
        /**生成URL
     * @param $page_num
     * @return string
     */
    function getUrl($page_num){
        $get=$_GET;
        if(isset($get['page']))unset($get['page']);
        $return='/'.$get['mod'];
        $return.='/'.$get['action'];
        foreach($get as $key=>$value){
            if(!in_array($key,array('mod','action'))&&$value){
                $return.='/'.$key.'/'.$value;
            }
        }
        $return.='/page/'.$page_num;
        return PAGE_BASE.$return;
    }
}
?>