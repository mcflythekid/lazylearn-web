<?php
require __DIR__ . '/config.php';
$VERSION = 57;
$CTX = "";
$TITLE = "Lazylearn";
date_default_timezone_set("UTC");
if (is_dev()){
	$ASSET =  "/res_" . rand(1,10000);
} else {
	$ASSET =  "/res_" . $VERSION;
}
require_once __DIR__ . '/component/top.php';
require_once __DIR__ . '/component/bottom.php';
function is_dev() {
	$whitelist = array( '127.0.0.1', 'localhost', 'lazylearn-web' );
	if( in_array( $_SERVER['SERVER_NAME'], $whitelist) )
		return true;
	return false;
}
function title($str){
	global $TITLE;
    $TITLE = $str;
}
