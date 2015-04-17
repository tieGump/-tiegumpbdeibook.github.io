<?php
session_start();
error_reporting(E_ALL );
ini_set("display_errors", '1');
define('ROOT', substr(dirname(__FILE__), 0).'/');
require 'public/config.php';
date_default_timezone_set('Asia/Shanghai');
require PUBLIC_DIR.'function.php';
require SRC_DIR.'/Cop.php';
Cop::start();