<?php
	require_once __DIR__ . '/deploy.php';
	date_default_timezone_set("UTC");
	$ASSET =  "/40";
	$REMEMBER_ME_DAY = 30;
	$FORGET_PASSWORD_DAY = 1;
	$LEITNER[1] = 86400; // 1 day
	$LEITNER[2] = 259200; // 3 days
	$LEITNER[3] = 604800; // 7 days
	$LEITNER[4] = 2592000; // 30 days
	$LEITNER[5] = 10368000; // 120 days
	$LEITNER[6] = 63072000; // 730 days