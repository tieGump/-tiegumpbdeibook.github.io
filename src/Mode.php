<?php
/**模型
 * Class Mode
 * @author tie.Gump
 */
abstract class Mode
{
    /**表名
     * @var string
     */
    protected $_table;
    /**数据库句柄
     * @var mixed
     */
    protected $_db;
    /**T句柄
     * @var T
     */
    protected $_t;
    /**表的主键
     * @var string
     */
    protected $_table_id;
    /**表的字段
     * @var
     */
    protected $_fields;
    function __construct()
    {
        $this->_db = DB::getDB();
        $this->_t = new T($this->_table);
        $this->_table_id=$this->_t->getTableId();
        $this->_fields=$this->_t->getTableFields();
    }

    /**修改数据库的一行
     * @param $data
     * @param $where
     * @return mixed
     */
    function changeOne($data, $where)
    {
        $where = $this->createWhere($where);
        return $this->_db->changeOne($this->_table,$data,$where);
    }

    /**
     * 修改一行
     * @param $filed_name
     * @param $value
     * @param $where
     * @return mixed
     */
    function changeOneFiled($filed_name,$value,$where){
        $data[$filed_name]=$value;
        return $this->changeOne($data,$where);
    }
    /**删除
     * @param $where
     * @return mixed
     */
    function delete($where)
    {
        $where = $this->createWhere($where);
        if(!$where)
            return ;
        return $this->_db->delete($this->_table, $where);
    }

    /**增加
     * @param $data
     * @return mixed
     */
    function addOne($data)
    {
        return $this->_db->addOne($this->_table, $data);
    }

    /**查询
     * @param $id
     * @return mixed
     */
    function getOne($id){
        return $this->_db->getOne($this->_table,$this->createWhere($id));
    }

    /**创建WHERE条件
     * @param $id_string
     * @return string
     */
    function createWhere($id_string)
    {
        $where = '';
        if (is_numeric($id_string)) {
            $where = $this->_table_id . '=' . (int)$id_string;
        } elseif (is_string($id_string)) {
            $where = $id_string;
        }
        return $where;
    }

    /**
     * 获取分页的信息
     * @param $str_sql
     * @param int $page_size
     * @return mixed
     */
    function getPageList($str_sql,$page_size=20){
        $get_page = (isset($_GET['page']) && $_GET['page']) ? $_GET['page'] : 1;
        $page = new Page($str_sql, $get_page, $page_size);
        return $page->get();
    }

    /**
     * 检查isset
     * @param $checkInfo
     * @return bool
     */
    function checkSet($checkInfo){
        return isset($checkInfo)&&$checkInfo;
    }
    /**
     * xigou
     */
    function __destruct(){
        unset($this->db,$this->_t,$this->_table);
    }
} 