<?php

/**
 * 权限
 * Class Permission
 * @author tie.Gump
 */
class Permission extends Mode
{
    private $_permission_array;

    function __construct()
    {
        $this->_table = 'bdei_permission';
        $this->_table_id = 'permission_id';
        parent::__construct();
    }

    /**
     * 用户权限
     * @param $user_id
     * @return array
     */
    function getUserPermission($user_id, $page_name = '')
    {
        $str_sql = 'SELECT p.permission_id,p.permission_name,p.permission_union_key,p.page_name,p.parent_id FROM ' . $this->_table . ' ,bdei_user_permission up WHERE up.permission_id=p.permission_id AND up.user_id=' . $user_id;
        if ($page_name)
            $str_sql .= ' AND p.page_name=' . $page_name;
        return $this->_db->doSelect($str_sql);
    }

    /**
     * 用户组权限
     */
    function getGroupPermission($group_id, $page_name = '')
    {
        $str_sql = 'SELECT p.permission_id,p.permission_name,p.permission_union_key,p.page_name,p.parent_id FROM ' . $this->_table . ' ,bdei_group_permission gp WHERE gp.permission_id=p.permission_id AND gp.group_id=' . $group_id;
        if ($page_name)
            $str_sql .= ' AND p.page_name=' . $page_name;
        return $this->_db->doSelect($str_sql);
    }

    /**
     * 获取所有的权限
     * @param $user_id
     * @param $group_id
     * @param string $page_name
     * @return array
     */
    function getPermission($user_id, $group_id, $page_name = '')
    {
        $group_permission = $this->getGroupPermission($group_id, $page_name);
        $user_permission = $this->getUserPermission($user_id, $page_name);
        $permission = array_merge($group_permission, $user_permission);
        return $permission;
    }

    /**
     * 创建页面的权限
     * @param $user_id
     * @param $group_id
     * @param $page_name
     */
    function getPagePermission($user_id, $group_id, $page_name)
    {
        $permission = $this->getPermission($user_id, $group_id, $page_name);
        $this->_permission_array = Arrays::downArray($permission, 'permission_union_key');
    }

    /**
     * 检查权限
     * @param $permission_name
     * @return bool
     */
    function checkPermission($permission_name)
    {
        return in_array($permission_name, $this->_permission_array);
    }

    /**
     * 获取所有的权限
     * @return array
     */
    function getAllPermission()
    {
        $str_sql = 'SELECT ' . $this->_fields . ' FROM ' . $this->_table;
        return $this->_db->doSelect($str_sql);
    }

    /**
     * 删除用户组的权限
     * @param $group_id
     */
    function deleteGroupPermission($group_id)
    {
        return $this->_db->delete('bdei_group_permission', 'group_id=' . $group_id);
    }

    /**删除用户的权限
     * @param $user_id
     */
    function deleteUserPermission($user_id)
    {
        return $this->_db->delete('bdei_user_permission', 'user_id=' . $user_id);
    }

    /**
     * 修改用户的权限
     * @param $user_id
     * @param $permission_array
     * @return int
     */
    function editUserPermission($user_id, $permission_array)
    {
        $this->deleteUserPermission($user_id);
        $insert_str = '';
        foreach ($permission_array as $value) {
            if (!$value) continue;
            $insert_str .= ",($user_id,$value)";
        }
        if ($insert_str) {
            $str_sql = 'INSERT INTO bdei_user_permission(user_id,permission_id)VALUES' . substr($insert_str, 1);
            $this->_db->query($str_sql);
        }
        return $this->_db->getQueryNumber();
    }

    /**修改用户组的权限
     * @param $group_id
     * @param $permission_array
     * @return int
     */
    function editGroupPermission($group_id, $permission_array)
    {
        $this->deleteGroupPermission($group_id);
        $insert_str = '';
        foreach ($permission_array as $value) {
            if (!$value) continue;
            $insert_str .= ",($group_id,$value)";
        }
        if ($insert_str) {
            $str_sql = 'INSERT INTO bdei_group_permission(group_id,permission_id)VALUES' . substr($insert_str, 1);
            $this->_db->query($str_sql);
        }
        return $this->_db->getQueryNumber();
    }

    function createDeepTree()
    {

    }
}