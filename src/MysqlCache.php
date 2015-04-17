<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-3-25
 * Time: 涓??10:32
 */

/**mysql类型的缓存
 * Class MysqlCache
 * @author tie.Gump
 */
class MysqlCache implements Cache
{
    private $_table = 'staff_cache';
    private $_db;

    function __construct()
    {
        $this->init();
    }

    /**
     * 初始化
     */
    function init()
    {
        $this->_db = DB::getDB();

        if ($this->_db->getRowNumber("SHOW TABLES LIKE '" . $this->_table . "'") == 1) {

        } else {
            $this->createTable();
        }
    }

    /**
     * 创建缓存表
     */
    function createTable()
    {
        $str_sql = ' CREATE TABLE `cache` (
`cache_id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`cache_key` VARCHAR( 64 ) NOT NULL ,
`cache_value` TEXT NOT NULL ,
UNIQUE (
`cache_key`
)
) ENGINE = MYISAM';
        $this->_db->query($str_sql);
    }

    /**拿取数据
     * @param $key
     * @return mixed|string
     */
    function get($key)
    {
        $info = $this->_db->getOne($this->_table, 'cache_key="' . md5($key) . '"');
        return isset($info['cache_value']) ? unserialize($info['cache_value']) : '';
    }

    /**存入数据
     * @param $key
     * @param $value
     */
    function set($key, $value)
    {
        $info = $this->_db->getOne($this->_table, 'cache_key="' . md5($key) . '"');
        if ($info) {
            $this->replace($key, $value);
        } else {
            $data = array('cache_key' => md5($key), 'cache_value' => serialize($value));
            $this->_db->addOne($this->_table, $data);
        }
    }

    /**删除数据
     * @param $key
     */
    function delete($key)
    {
        $this->_db->delete($this->_table, 'cache_key="' . md5($key) . '"');
    }

    /**替换数据
     * @param $key
     * @param $value
     */
    function replace($key, $value)
    {
        $this->_db->changeOne($this->_table, array('cache_value' => serialize($value)), 'cache_key="' . md5($key) . '"');
    }
    /**
     * 清空缓存数据
     * @author Howard
     */
    public function flush () {
        $this->_db->query('TRUNCATE TABLE ' . $this->_table);
        return true;
    }
}