<?php

/**
 * 数据库单表操作
 * <?php
 * //实例化一个T的实例，第一个参数为表名称，第二个参数为主键的ID，或者是能确定唯一取一条where条件
 * $t=new T('hotel',1);
 * //通过表的字段名称直接获取这条唯一标识的值
 * echo $t->hotel_name;
 * //可以通过text 和 textarea 的前缀+表字段名称（支持驼峰跟_两种名称规则）的变量 画出文本框跟文本域,暂时只支持这两个
 * echo $t->text_hotel_name;
 * //可以通过text(文本框) textarea(文本域)和select(下拉菜单) 的前缀+表字段名称（支持驼峰跟_两种名称规则）组成的方法划出相应的HTML，暂时支持这三个
 * //text暂时不支持传递参数,textarea支持传递宽高,select必须传递要实现下拉的数组（Fool::drawSelect的数组规则）
 * $t->textareaHotelName(50,10);
 * $_star = array('不限', '1星', '经济型', '3星', '4星', '5星', '6星');
 * $t->selectHotelStars($_star);
 * //可以通过对字段名直接赋值进行对表字段的修改
 * $t->hotel_name="simple test";
 * ?>
 * @author tie.gump
 * @package 数据库
 */
class T
{
    private $_db;
    private $_id;
    private $_table_id;
    private $_field;
    private $_table;
    private $_old_data = array();
    private $_new_data = array();
    private $_base_type = array('select', 'textarea', 'text');

    function __construct($table_name, $id = '')
    {
        $this->_db = DB::getDb();
        $this->_table = $table_name;
        $this->_id = $id;
        $this->getTableInfo();
        $str_sql='SELECT '.$this->_field.' FROM '.$this->_table.' WHERE 1 ';
        if(!$id){
            return;
        }elseif (is_numeric($id)) {
            $str_sql.=' AND '.($this->_table_id . '=' . $id);
        } elseif (is_string($id)) {
            $str_sql.=' AND '.$id;
        } else {
            die('暂时不支持其他关联唯一表数据抽取的类型');
        }
        $info = $this->_db->doSelect($str_sql.' LIMIT 1');
        if (!$info)
            die('不存在的ID属性！！！');
        $this->_old_data = $this->_new_data = $info[0];

    }

    function getTableId()
    {
        return $this->_table_id;
    }

    function getTableFields()
    {
        return $this->_field;
    }

    private function getTableInfo()
    {
        $field_str = '';
        $str_sql = 'desc ' . $this->_table;
        $info = $this->_db->doSelect($str_sql);
        if ($info) {
            foreach ($info as $row) {
                if ($row['Key'] == 'PRI') {
                    $this->_table_id = $row['Field'];
                }
                $field_str .= ',' . $row['Field'];
            }
            $this->_field = substr($field_str, 1);
        } else {
            die($this->_table.'不存在的表！！！');
        }

    }

    function __set($name, $value)
    {
        if (isset($this->_old_data[$name]) && $this->_old_data[$name] != $value) {
            $this->_old_data[$name] = $value;
            $this->_new_data[$name] = $value;
        }
    }

    function __get($name)
    {
        if (isset($this->_old_data[$name])) {
            return $this->_old_data[$name];
        } else {
            if (count($arr_tmp = explode('text', $name)) > 1) { //文本框
                $fields_name = $this->createField($arr_tmp[1]);
                if (isset($this->_old_data[$fields_name])) {
                    return html::input_field($fields_name, $this->_old_data[$fields_name]);
                }
            }
            if (count($arr_tmp = explode('textarea', $name)) > 1) { //文本域
                $fields_name = $this->createField($arr_tmp[1]);
                if (isset($this->_old_data[$fields_name])) {
                    return html::textarea_field($fields_name, '', 50, 10, $this->_old_data[$fields_name]);
                }
            }
        }
    }

    function __call($name, $arguments)
    {
        $name_arr = $this->createName($name);
        $fields_name = $name_arr['name'];
        if (isset($this->_old_data[$fields_name])) {
            switch ($name_arr['type']) {
                case 'text': //文本框
                    echo html::input_field($fields_name, $this->_old_data[$fields_name]);
                    return;
                    break;
                case 'textarea': //文本域
                    echo html::textarea_field($fields_name, '', isset($arguments[0]) ? $arguments[0] : '50', isset($arguments[1]) ? $arguments[1] : '10', $this->_old_data[$fields_name]);
                    return;
                    break;
                case 'select': //下拉框
                    if (isset($arguments[0]) && is_array($arguments[0])) {
                        echo html::drawSelect($arguments[0], $fields_name, $this->_old_data[$fields_name]);
                        return;
                    }
                    break;
                case 'hide': //隐藏框
                    echo html::input_hidden_field($fields_name, $this->_old_data[$fields_name]);
                    return;
                    break;
                case 'check': //复选框
                    echo html::input_checkbox_field($fields_name, $this->_old_data[$fields_name], isset($arguments[0]) ? $arguments[0] : $this->_old_data[$fields_name]);
                    return;
                    break;
                case 'radio': //单选框
                    echo html::input_radio_field($fields_name, $this->_old_data[$fields_name], isset($arguments[0]) ? $arguments[0] : $this->_old_data[$fields_name]);
                    return;
                    break;
                default:
                    echo '不存在的方法';
            }
            echo '不存在的方法';
        }
    }

    private function createName($name)
    {
        foreach ($this->_base_type as $value) {
            if (strpos($name, $value) === 0) {
                $return['type'] = $value;
                $return['name'] = $this->createField(substr($name, strlen($value)));
                break;
            }
        }
        return $return;
    }

    private function createField($name)
    {
        $name = preg_replace('/[A-Z]/', '_${0}', $name);
        $name = substr($name, 1);
        return strtolower($name);
    }

    function __destruct()
    {
        if ($this->_new_data) {
            if (is_numeric($this->_id)) {
                $where = ($this->_table_id . '=' . $this->_id);
            } elseif (is_string($this->_id)) {
                $where = ($this->_id);
            }
            $this->_db->update($this->_table, $this->_new_data, $where);
        }
    }
}

?>