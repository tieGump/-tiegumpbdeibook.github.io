<?php
/**
 * SESSION会话函数
 */
if ( (PHP_VERSION >= 4.3) && ((bool)ini_get('register_globals') == false) ) {
    @ini_set('session.bug_compat_42', 1);
    @ini_set('session.bug_compat_warn', 0);
}
class Session {
    protected $savePath;
    protected $sessionName;
    private $_db_hand;
    private $_table='sessions';
    private $_max_time=3600;
    public function __construct($db_name=DB_DATABASE) {
        $this->_db_hand=DB::getDB($db_name);
        session_set_save_handler(
            array($this, "open"),
            array($this, "close"),
            array($this, "read"),
            array($this, "write"),
            array($this, "destroy"),
            array($this, "gc")
        );
    }

    public function open() {
        return true;
    }

    public function close() {
        // your code if any
        return true;
    }
    /**
     * 读取SESSION
     * @param int $id session id
     * @return string string of the sessoin
     */
    public function read($id) {
        $info=$this->_db_hand->getOne($this->_table,'sesskey1 ="'.$id.'" and expiry > "'.time().'"');
        if($info){
            return $info['value'];
        }
        return '';
    }

    /**
     * 写入SESSION
     *
     * @param int    $id session id
     * @param string $data of the session
     */
    public function write($id, $data) {
        $expiry = time() + $this->_max_time;
        $str_sql = 'REPLACE INTO '.$this->_table.' SET `sesskey`="'.addslashes($id).'", `expiry` = "'.addslashes($expiry).'", `value` = "'.addslashes($data).'" ';
        return $this->_db_hand->query($str_sql);
    }
    /**
     * 销毁session
     * @param int $id session id
     * @return bool
     */
    public function destroy($id) {
        $query = 'DELETE FROM '.$this->_table.' WHERE sesskey="'.addslashes($id).'" ';
        return $this->_db_hand->query($query);
    }
    /**
     * 销毁过期的session
     * @param int $maxlifetime
     */
    public function gc($maxlifetime) {
        return $this->_db_hand->delete($this->_table,'expiry < '.time());
    }
}

