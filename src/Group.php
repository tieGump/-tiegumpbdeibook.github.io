<?php

class Group extends Mode{
    function __construct(){
        $this->_table='bdei_group';
        $this->_table_id='group_id';
        parent::__construct();
    }

    /**
     * 获取用户列表 type为1的不分页
     * @param int $type
     * @param array $get
     * @return array|mixed
     */
    function getList($type=1,$get=array()){
        $where = 1;
        if($this->checkSet($get['group_name'])){
            $where.='AND group_name LIKE "'.$get['group_name'].'"';
        }
        $str_sql='SELECT '.$this->_fields.' FROM '.$this->_table.' WHERE '.$where;
        if($type==1)
            return $this->_db->doSelect($str_sql);
        else
            return $this->getPageList($str_sql);

    }
    function addGroup($group_name){
        if(!$group_name)
            return 0;
        $data['group_name']=$group_name;
        return $this->addOne($data);
    }
    function deleteGroup($group_id){
        $this->delete($group_id);
        $permission=new Permission();
        $permission->deleteGroupPermission($permission);
    }
}