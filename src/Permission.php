<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-3-27
 * Time: 下午2:15
 */

class Permission extends Mode{
    function __construct(){
        $this->_table='staff_permission';
        parent::__construct();
    }
    function checkPermission($permission_id){

    }
    function getUserPermission($user_id='',$group_id=''){


    }

}