<?php

class DownloadSoft extends Mode{
    function __construct(){
        $this->_table='bdei_soft';
        $this->_table_id='soft_id';
        parent::__construct();
    }
    function input($post ,$id=''){
        $data['soft_name']=$post['soft_name'];
        $data['soft_pic']=$post['soft_pic'];
        $data['soft_save_place']=$post['soft_save_place'];
        if($id){
            $this->changeOne($data,$id);
        }else{
            $id=$this->addOne($data);
        }
        return $id;
    }
} 