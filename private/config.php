<?php
	//require_once dirname(__DIR__) . '/vendor/autoload.php';
	//csrfProtector::$cookieExpiryTime = 2147483647; // Forever, hahaha ^^
	//csrfProtector::init();
	
	
	
	
	
	date_default_timezone_set("UTC");
	$SALT = "CT3fkc2LyRYTk9GNXu9gjW";
	$ASSET =  "/38";
	$MYSQL_SERVER = "127.0.0.1";
	$MYSQL_USER = "lazylearn";
	$MYSQL_PASS = "laplaplon1";
	$MYSQL_DB = "lazylearn";
	$REMEMBER_ME_DAY = 30;
	$FORGET_PASSWORD_DAY = 1;
	$LEITNER[1] = 86400;
	$LEITNER[2] = 259200;
	$LEITNER[3] = 604800;
	$LEITNER[4] = 2592000;
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
?>