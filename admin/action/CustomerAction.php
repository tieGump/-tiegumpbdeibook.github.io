<?php
class CustomerAction extends Action{
    function indexAction(){
        $user=new User();
        $this->user_list=$user->getList();
    }
}