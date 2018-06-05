<?php
$VERSION = 70 ;
$CTX = "";
$HEADER = "";
$HEADER2 = "";
$CTX = "";
$TITLE = "Lazylearn";
$TIMEZONE = "UTC";
//////////////////////////////////////////////////////////////////////////////////////
if (is_dev()){
	$ASSET =  "/res_" . rand(1,10000);
	$ENDPOINT = 'http://localhost:8888/lazylearn-api';
} else {
	$ASSET =  "/res_" . $VERSION;
	$ENDPOINT = 'https://lazylearn.com:8081/lazylearn-api';
}
///////////////////////////////////////////////////////////////////////////////////////
date_default_timezone_set($TIMEZONE);
require_once __DIR__ . '/component/top_private.php';
require_once __DIR__ . '/component/bottom_private.php';
require_once __DIR__ . '/component/top_public.php';
require_once __DIR__ . '/component/bottom_public.php';
////////////////////////////////////////////////////////////////////////////////////////
function is_dev() {
	$whitelist = array( '127.0.0.1', 'localhost', 'local.lazylearn.com' );
	if( in_array( $_SERVER['SERVER_NAME'], $whitelist) )
		return true;
	return false;
}
function title($str){
	global $TITLE;
    $TITLE = $str;
}
//////////////////////////////////////////////////////////////////////////////////////////