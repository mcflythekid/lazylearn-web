<?php
/**********************************************************************************/
const TIMEZONE = "UTC";
const VERSION = 116;
/**********************************************************************************/

require_once __DIR__ . '/_env.php';
$HEADER = $HEADER2 = $TITLE = '';
$API_SERVER = API_SERVER;
if (!IS_PRODUCTION) {
    $ASSET =  "/dkmm-" . rand(100000,999999);
} else {
    $ASSET =  "/dkmm-" . VERSION;
}
date_default_timezone_set(TIMEZONE);
require_once __DIR__ . '/component/top_private.php';
require_once __DIR__ . '/component/bottom_private.php';
require_once __DIR__ . '/component/top_public.php';
require_once __DIR__ . '/component/bottom_public.php';
require_once __DIR__ . '/component/asset.php';
require_once __DIR__ . '/component/Vocabdeck.php';
require_once __DIR__ . '/component/Vocab.php';
require_once __DIR__ . '/component/Deck.php';
require_once __DIR__ . '/component/Card.php';
require_once __DIR__ . '/component/Minpair.php';
require_once __DIR__ . '/component/Article.php';
/**********************************************************************************/
function escape($str){
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}
/**********************************************************************************/
