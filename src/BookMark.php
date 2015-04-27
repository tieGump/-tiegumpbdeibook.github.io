<?php

/**
 * 用户书签管理
 * Class BookMark
 * @author tie.Gump
 */
class BookMark extends Mode{
    function __construct(){
        $this->_table='bdei_bookmark';
        $this->_table_id='bdei_';
    }
}