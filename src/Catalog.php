<?php

/**
 * 目录
 * Class Catalog
 * @author tie.Gump
 */
class Catalog extends Mode
{

    function __construct()
    {
        $this->_table = 'bdei_book_category';
        $this->_table_id = 'dir_id';
        parent::__construct();
    }


    /**
     * 生成OPTION的无线处理
     * @param array $arr 查询出来的数组
     * @param int|string $select 选中的项
     * @param $check_id
     * @param int $parent_id 父级ID
     * @param int $deep 深度
     * @return string
     */
    function getOptionShow($arr, $select = '', $check_id, $parent_id = 0, $deep = 0)
    {

        $str_need = '';
        $str_nbsp = '';
        $select_str = '';
        for ($i = 0; $i < $deep; $i++) {
            $str_nbsp .= '&nbsp;&nbsp;';
        }
        $deep++;
        foreach ($arr as $key => $value) {
            if ($select) {
                $select_str = ($select == $value['dir_id']) ? 'selected' : '';
            }
            if ($value['parent_id'] == $parent_id) {
                unset($arr[$key]);
                $check_str = '';
                if ($check_id && $check_id == $value['dir_id']) {
                    $check_str = 'selected';
                }
                $str_need .= '<option value="' . $value['dir_id'] . '" ' . $select_str . ' ' . $check_str . '>' . $str_nbsp . $value['dir_name'] . '</option>';

                $str_need .= $this->getOptionShow($arr, $select, $check_id, $value['dir_id'], $deep);
            }
        }
        return $str_need;
    }

    /**获得select 的option数据
     * @param string $select
     * @param int $check_id
     * @return string
     */
    function getOption($select = '', $check_id = 0)
    {
        $str_sql = 'SELECT ' . $this->_fields . ' FROM ' . $this->_table;
        if ($arr = $this->_db->doSelect($str_sql))
            return $this->getOptionShow($arr, $select, $check_id);
    }

    /**返硕到最顶级
     * @param $id
     * @return array
     */
    function getUp($id)
    {
        $return = array();
        $return[] = $info = $this->getOne($id);
        if (isset($info['parent_id']) && $info['parent_id'] != 0) {
            $tmp = $this->getUp($info['parent_id']);
            $return = array_merge($tmp, $return);
        }
        return $return;
    }

    /**创建上级的SELECT
     * @param $catalog_arr
     * @param string $change_function
     * @return array
     */
    function createUpSelect($catalog_arr, $change_function = '')
    {
        foreach ($catalog_arr as $key => $value) {
            $return[] = '<select name="catalog_list_' . $key . '" ' . (($change_function) ? 'onchange="' . $change_function . '"' : '') . '><option value="">请选择</option>' . $this->createUpOption($value['parent_id'], $value['dir_id']) . '</select>';
        }
        return $return;
    }

    /**创建上级的OPTION
     * @param $parent_id
     * @param string $default
     * @return string
     */
    function createUpOption($parent_id, $default = '')
    {
        $info = $this->getBaseInfo('', $parent_id);
        $str_return = '';
        foreach ($info as $value) {
            $selected = $value['dir_id'] == $default ? 'selected' : '';
            $str_return .= '<option value="' . $value['dir_id'] . '" ' . $selected . '>' . $value['dir_name'] . '</option>';
        }
        return $str_return;
    }

    /**增加
     * @param array $info
     * @param $parent_id
     * @return mixed
     */
    function addList(array $info, $parent_id)
    {
        if (!isset($info['add_class_name']) && $info['add_class_name'])
            return;
        $data['dir_name'] = $info['add_class_name'];
//        $data['sort_num'] = $info['sort_num'];
        $data['r_groups_id'] = $info['r_permission'];
        $data['rw_groups_id'] = $info['rw_permission'];
        $data['rwd_groups_id'] = $info['rwd_permission'];
        $data['parent_id'] = $parent_id;
        $insert_id = $this->addOne($data);
        if ($insert_id) {
            $this->dropGroupCache($data['r_groups_id']);
            return $insert_id;
        } else {
            return false;
        }
    }

    /**修改
     * @param array $info
     * @param $id
     * @return mixed
     */
    function changeList(array $info, $id)
    {
        if (!isset($info['dir_name']) && $info['dir_name'])
            return;
        $data['dir_name'] = $info['dir_name'];
        $data['sort_num'] = $info['sort_num'];
        $query_num = $this->changeOne($data, $id);
        return $query_num;
    }

    /**删除
     * @param $id
     */
    function drop($id)
    {
        $son = $this->getSon($id);
        foreach ($son as $v) {
            $this->delete($v['dir_id']);
        }
        $this->delete($id);
    }

    /**获取一个
     * @param $id
     * @return array
     */
    function getOne($id)
    {
        $str_sql = 'SELECT '.$this->_fields.' FROM ' . $this->_table . ' WHERE dir_id=' . (int)$id . ' LIMIT 1';
        $info = $this->_db->doSelect($str_sql);
        return $info ? $info[0] : array();
    }

    /**获取子目录的递归
     * @param $arr
     * @param $parent_id
     * @return array
     */
    function getSonTmp($arr, $parent_id)
    {
        $arr_return = array();
        foreach ($arr as $key => $value) {
            if ($value['parent_id'] == $parent_id) {
                $arr_return[] = $value;
                unset($arr[$key]);
                $arr_tmp = $this->getSonTmp($arr, $value['dir_id']);
                if ($arr_tmp)
                    $arr_return = array_merge($arr_return, $arr_tmp);
            }
        }
        return $arr_return;
    }

    /**获取所有的目录
     * @return mixed
     */
    function getAllList()
    {
        $str_sql = 'SELECT ' . $this->_fields . ' FROM ' . $this->_table;//. ' WHERE FIND_IN_SET(' . $group_id . ',rw_groups_id)';
        return $this->_db->doSelect($str_sql);
    }

    /**获取基本信息。
     * @param string $dir_id
     * @param string $parent_id
     * @return bool
     */
    function getBaseInfo($dir_id = '', $parent_id = '')
    {
        if ($dir_id) {
            if (is_numeric($dir_id))
                $str_sql = 'SELECT * FROM ' . $this->_table . ' WHERE dir_id=' . (int)$dir_id;
            elseif (is_string($dir_id)) {
                $str_sql = 'SELECT * FROM ' . $this->_table . ' WHERE dir_id IN (' . $dir_id . ')';
            } elseif (is_array($dir_id)) {
                $str_sql = 'SELECT * FROM ' . $this->_table . ' WHERE dir_id IN (' . join(',', $dir_id) . ')';
            }
        } elseif (!is_null($parent_id)) {
            $str_sql = 'SELECT * FROM ' . $this->_table . ' WHERE parent_id=' . (int)$parent_id;
        } else {
            return false;
        }
        return $this->_db->doSelect($str_sql);
    }
}