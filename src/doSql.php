<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-3-24
 * Time: 下午1:02
 */

/**
 * 简单数据库处理类
 * Class doSql
 * @author tie.Gump
 */
class doSql
{
    /**数据库连接的句柄
     * @var
     */
    private $_db_hand;
    /**需要进行优化操作的表的集合
     * @var array
     */
    private $_optimize = array();

    function __construct($db_name = DB_DATABASE,$type=1)
    {
        $this->connect($db_name,$type);
    }

    /**
     * 安全输入
     * @param $string
     * @return string
     */
    final public function input($string) {
        if (function_exists('mysql_real_escape_string')) {
            return mysql_real_escape_string($string);
        } elseif (function_exists('mysql_escape_string')) {
            return mysql_escape_string($string);
        }
        if(!get_magic_quotes_gpc()){
            return addslashes($string);
        }
        return $string;
    }
    /**连接数据库
     * @param $db_name 数据库名称
     * @param $type
     */
    function connect($db_name,$type)
    {
        $this->_db_hand = mysql_connect(DB_SERVER, DB_SERVER_USERNAME, DB_SERVER_PASSWORD, true, MYSQL_CLIENT_COMPRESS) or die('Unable to connect to database server!');
        mysql_select_db($db_name);
        if($type==1)
            mysql_set_charset('utf8', $this->_db_hand);
    }

    /**增加操作
     * @param $table 表名
     * @param $data 数据
     * @return int
     */
    function addOne($table, $data)
    {
        $value = $this->arrToSql($data);
        if ($value) {
            $this->_optimize[] = $table;
            $str_sql = 'INSERT INTO ' . $table . ' SET ' . $value;
            $this->query($str_sql);
            return $this->getInsertId();
        }
    }

    /**修改操作
     * @param $table 表名称
     * @param $data 数据
     * @param $where where条件
     * @return int
     */
    function changeOne($table, $data, $where)
    {
        $value = $this->arrToSql($data);

        if ($value) {
            $str_sql = 'UPDATE ' . $table . ' SET ' . $value . ' WHERE ' . $where;
//            echo $str_sql;
            $this->query($str_sql);
            return $this->getQueryNumber();
        }
    }

    /**把数组解析成能执行的SQL
     * @param $arr
     * @return string
     */
    function arrToSql($arr)
    {
        $str = '';
        foreach ($arr as $key => $value) {
            if(substr($str, -1)=='='){
                $str .= ", $key $value";
            }else{
                $str .= ',' . $key . "='$value'";
            }

        }
        return $str ? substr($str, 1) : '';
    }

    /**拿取一条记录
     * @param $table 表名
     * @param $where where条件
     * @return array
     */
    function getOne($table, $where)
    {
        $str_sql = 'SELECT * FROM ' . $table . ' WHERE ' . $where;
        return mysql_fetch_array($this->query($str_sql), MYSQL_ASSOC);
    }

    /**查询操作
     * @param $str_sql 执行的SQL语句
     * @return array
     */
    function doSelect($str_sql)
    {
        $return = array();
        $query = $this->query($str_sql);
        while ($row = mysql_fetch_array($query, MYSQL_ASSOC)) {
            $return[] = $row;
        }
        return $return;
    }

    /**mysql 执行
     * @param $str_sql
     * @return resource
     */
    function query($str_sql)
    {
        $hand = mysql_query($str_sql, $this->_db_hand);
        if ($error = mysql_error()) {
            echo $str_sql.'<br />'.$error;
            exit;
        } else {
            return $hand;
        }
    }

    /**删除
     * @param $table 表名称
     * @param $where 删除条件
     */
    function delete($table, $where)
    {
        $this->_optimize[] = $table;
        $str_sql = 'DELETE FROM ' . $table . ' WHERE ' . $where;
        $this->query($str_sql);
    }

    /**获取插入id
     * @return int
     */
    function getInsertId()
    {
        return mysql_insert_id();
    }

    /**获取影响的条数
     * @return int
     */
    function getQueryNumber()
    {
        return mysql_affected_rows();
    }

    /**返回执行条数
     * @param $str_sql 传入的SQL语句
     * @return int
     */
    function getRowNumber($str_sql)
    {
        return mysql_num_rows($this->query($str_sql));
    }

    /**
     * 执行优化表操作
     */
    function optimize()
    {
        if (!$this->_optimize)
            return false;
        $arr = array_unique($this->_optimize);
        $arr = clearOneArray($arr);
        $table = '';
        foreach ($arr as $value) {
            $table .= ',`' . $value . '`';
        }
        if ($table) {
            //$str_sql = 'OPTIMIZE TABLE ' . substr($table, 1);
            //$this->query($str_sql);
        }
    }

    /**
     * 析构函数，执行表优化动作
     */
    function __destruct()
    {
        $this->optimize();
    }
}