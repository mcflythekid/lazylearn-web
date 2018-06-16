<?php
/**********************************************************************************/
const TIMEZONE = "UTC";
const VERSION = 90;
const DEV_DOMAIN = "local.lazylearn.com";
const API_SERVER_DEV = 'http://localhost:8888/lazylearn-api';
const API_SERVER_PRO = 'https://lazylearn.com:8081/lazylearn-api';
/**********************************************************************************/
$HEADER = $HEADER2 = $TITLE = '';
if( in_array( $_SERVER['SERVER_NAME'], array(DEV_DOMAIN)) ){
    $API_SERVER = API_SERVER_DEV;
    $ASSET =  "/dkmm-" . rand(100000,999999);
} else {
    $API_SERVER = API_SERVER_PRO;
    $ASSET =  "/dkmm-" . VERSION;
}
date_default_timezone_set(TIMEZONE);
require_once __DIR__ . '/component/top_private.php';
require_once __DIR__ . '/component/bottom_private.php';
require_once __DIR__ . '/component/top_public.php';
require_once __DIR__ . '/component/bottom_public.php';
require_once __DIR__ . '/component/asset.php';
require_once __DIR__ . '/component/modal.php';
/**********************************************************************************/