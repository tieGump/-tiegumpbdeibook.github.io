<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-5-1
 * Time: 下午8:55
 */

class Links extends Mode{
    function __construct(){
        $this->_table='bdei_links';
        $this->_table_id='links_id';
        parent::__construct();
    }
    function input($post,$id=''){
        $data['links_name']=$post['links_name'];
        $data['links_url']=$post['links_url'];

        if($id){
            $this->changeOne($data,$id);
        }else{
            $id=$this->addOne($data);
        }
        return $id;
    }
} 