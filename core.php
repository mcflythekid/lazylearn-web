<?php
$VERSION = 68 ;
$CTX = "";
$TITLE = "Lazylearn";
$TIMEZONE = "UTC";
//////////////////////////////////////////////////////////////////////////////////////
if (is_dev()){
	$ASSET =  "/res_" . rand(1,10000);
	$ENDPOINT = 'http://localhost:8088';
} else {
	$ASSET =  "/res_" . $VERSION;
	$ENDPOINT = 'https://beathim.com:8080/lazylearn-api';
}
///////////////////////////////////////////////////////////////////////////////////////
date_default_timezone_set($TIMEZONE);
require_once __DIR__ . '/component/top.php';
require_once __DIR__ . '/component/bottom.php';
////////////////////////////////////////////////////////////////////////////////////////
function is_dev() {
	$whitelist = array( '127.0.0.1', 'localhost', 'lazylearn.local' );
	if( in_array( $_SERVER['SERVER_NAME'], $whitelist) )
		return true;
	return false;
}
function title($str){
	global $TITLE;
    $TITLE = $str;
}
//////////////////////////////////////////////////////////////////////////////////////////