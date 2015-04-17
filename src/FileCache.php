<?php

/**简单的文件缓存系统
 * Class FileCache
 * @author tie.Gump
 */
class FileCache implements Cache
{
    private $_file_name;
    private $_data = array();

    function __construct()
    {
        $this->init();
    }

    /**
     * 初始化
     */
    function init()
    {
        $this->_file_name = CACHE_DIR . '/cache.php';
        if (!is_writable($this->_file_name)) {
            echo "缓存文件不可写，请检查！！";
        }
        $this->load();
    }

    function load()
    {
        if (!$this->_data) {
            require $this->_file_name;
            $this->_data = $arr_cache;
        }
    }

    function replace($key, $value)
    {
        $this->_data[md5($key)] = $value;
        file_put_contents($this->_file_name, '<?php $arr_cache = ' . var_export($this->_data, true) . '; ',LOCK_EX);
        clearstatcache();
    }

    function set($key, $value)
    {
        $this->replace($key, $value);
    }

    function get($key)
    {
        return $this->_data[md5($key)];
    }

    function delete($key)
    {
        unset($this->_data[md5($key)]);
        file_put_contents($this->_file_name, '<?php $arr_cache=' . var_export($this->_data, true) . '; ', LOCK_EX);
        clearstatcache();
    }
    function __destruct(){
        unset($this->_data);
    }
}
