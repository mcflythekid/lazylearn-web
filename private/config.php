<?php
	//require_once dirname(__DIR__) . '/vendor/autoload.php';
	//csrfProtector::$cookieExpiryTime = 2147483647; // Forever, hahaha ^^
	//csrfProtector::init();
	
	
	
	
	require_once __DIR__ . '/database.php';
	date_default_timezone_set("UTC");
	$SALT = "CT3fkc2LyRYTk9GNXu9gjW";
	$ASSET =  "/38";
	$REMEMBER_ME_DAY = 30;
	$FORGET_PASSWORD_DAY = 1;
	$LEITNER[1] = 86400;
	$LEITNER[2] = 259200;
	$LEITNER[3] = 604800;
	$LEITNER[4] = 2592000;
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
?>