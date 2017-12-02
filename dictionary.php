<?php
session_start();
header("Access-Control-Allow-Origin: *");
header('Content-type: application/json');
require_once("./private/config.php"); 
require_once("./private/lib.php");

if (!isset($_GET["word"])) quit();
$en = urldecode($_GET["word"]);
$en = strtolower($en);

$con = open_con();
$stmt = mysqli_prepare($con, "SELECT vi FROM ev WHERE en = ?;");
mysqli_stmt_bind_param($stmt, "s", $en);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
if (mysqli_num_rows($result) == 0) quit();
$row = mysqli_fetch_assoc($result);

if (isset($_SESSION["username"])){
	$extra = '';
	$vi = $row["vi"];
	$username = $_SESSION["username"];
	$set = db_object("select id from sets where username = '$username' and name = 'mcfly-dictionary'");
	if ($set){
		$set_id = $set['id'];
		$card = db_object("select id from cards where set_id = '$set_id' and front = '$en'");
		if ($card){
			$vi = $vi;//"<span style='font-weight:bold;'>$vi</>";
		} else {
			$encode_en = urlencode($en);
			$encode_vi = urlencode($vi);
			$extra = "&nbsp;&nbsp;&nbsp;<button stype='z-index: 999999;' class='mcfly-dictionary' data-en='$encode_en' data-vi='$encode_vi'><b>+ Tạo</b></button>";
		}
	} 
	
	echo json_encode([
		'vi' => $vi,
		'extra' => $extra
	]);
	
}else {
	echo json_encode([
		'vi' => $row['vi'],
		'extra' => ''
	]);
}

function quit(){
	echo ""; 
	exit;
}


function db_object($sql){
	global $con;
	$rs = mysqli_query($con, $sql);
	if (mysqli_num_rows($rs) > 0) {
		$obj = mysqli_fetch_assoc($rs); 
		return $obj;
	}
	return false;
}