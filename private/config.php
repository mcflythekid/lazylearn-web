<?php
	require_once __DIR__ . '/deploy.php';
	
	// configurations
	date_default_timezone_set("UTC");
	$VERSION = 50;
	$ROOT = "mcflythekid";
	$REMEMBER_ME_DAY = 30;
	$FORGET_PASSWORD_DAY = 1;
	$LEITNER[1] = 86400; // 1 day
	$LEITNER[2] = 259200; // 3 days
	$LEITNER[3] = 604800; // 7 days
	$LEITNER[4] = 2592000; // 30 days
	$LEITNER[5] = 10368000; // 4 months
	$LEITNER[6] = 63072000; // 2 years
	
	// auto	
	if (is_localhost()){
		$ASSET =  "/res_" . rand(1,10000);
	} else {
		$ASSET =  "/res_" . $VERSION;
	}
	$LEITNER_SIZE = sizeof($LEITNER);
	function is_localhost() {
		$whitelist = array( '127.0.0.1', 'localhost', 'lazylearn.localhost' );
		if( in_array( $_SERVER['SERVER_NAME'], $whitelist) )
			return true;
		return false;
	}