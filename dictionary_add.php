<?php
session_start();
header("Access-Control-Allow-Origin: *");
header('Content-type: application/json');
require_once("./private/config.php"); 
require_once("./private/lib.php");
require_once("./private/remember.php");

$post = json_decode(file_get_contents('php://input'), true);
if (!isset($post["front"])) quit(1);
if (!isset($post["back"])) quit(2);
if (!isset($_SESSION["username"])) quit(3);
$front = strtolower(urldecode($post["front"]));
$back = strtolower(urldecode($post["back"]));
$con = open_con();
$username = $_SESSION["username"];
$set = db_object("select id from sets where username = '$username' and name = 'mcfly-dictionary'");
if (!$set) quit();
$set_id = $set['id'];
$stmt = mysqli_prepare($con, "INSERT INTO cards (username, front, back, set_id) VALUES(?,?,?,?);");
mysqli_stmt_bind_param($stmt, "sssi", $username, $front, $back, $set_id);
mysqli_stmt_execute($stmt);
$new_id = mysqli_insert_id($con);
$stmt = mysqli_prepare($con, "UPDATE sets SET cards = cards + 1 WHERE id = ?;");
mysqli_stmt_bind_param($stmt, "i", $set_id);
mysqli_stmt_execute($stmt);
echo json_encode(['ok'=> 1]);

function quit($x){
	echo $x; 
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