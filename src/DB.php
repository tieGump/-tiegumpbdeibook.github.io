<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-3-26
 * Time: 上午11:20
 */

/**获取数据库的句柄
 * Class DB
 * @author tie.Gump
 */
class DB {
    /** 标记作用
     * @var doSql
     */
    private static $_hand;

    /**获取句柄
     * @param string $db_name
     * @return doSql
     */
    static function getDB($db_name=DB_DATABASE){
        if(isset(self::$_hand[$db_name])&&self::$_hand[$db_name] instanceof doSql )
            return self::$_hand[$db_name];
        else{
            self::$_hand[$db_name]=new doSql($db_name);
            return self::$_hand[$db_name];
        }

    }

    /**
     * 私有化掉构造函数
     */
    private function __construct(){

    }

    /**
     * 私有化掉克隆函数
     */
    private function __clone(){

    }
}