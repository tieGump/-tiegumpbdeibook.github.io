<?php

/**
 * 缓存接口
 * Interface Cache
 */
interface Cache{
    /**获取
     * @param $key
     * @return mixed
     */
    function get($key);

    /**设置
     * @param $key
     * @param $value
     * @return mixed
     */
    function set($key,$value);

    /**删除
     * @param $key
     * @return mixed
     */
    function delete($key);

    /**替换
     * @param $key
     * @param $value
     * @return mixed
     */
    function replace($key,$value);
}