<?php
session_start();
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-3-24
 */
error_reporting(E_ALL & ~E_NOTICE);
ini_set("display_errors", '1');
define('ROOT', substr(dirname(__FILE__), 0).'/');

require 'public/config.php';


require PUBLIC_DIR.'/function.php';
require SRC_DIR.'/Cop.php';
Cop::start();