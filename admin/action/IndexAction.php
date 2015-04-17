<?php

class IndexAction extends Action{
    function __construct(){
        parent::__construct();
    }

    function indexAction(){

        if(!Administrator::checkLogin()){
            redirect(Url);
        }
        $this->_data['admin_name']=$_SESSION['admin']['name'];
        $this->_tpl='index.html';
    }
} 