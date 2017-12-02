<?php
header("Access-Control-Allow-Origin: *");
require_once("./private/config.php"); 
require_once("./private/lib.php");

if (!isset($_GET["word"])) quit();
$en = urldecode($_GET["word"]);

$con = open_con();
$stmt = mysqli_prepare($con, "SELECT vi FROM ev WHERE en = ?;");
mysqli_stmt_bind_param($stmt, "s", $en);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
if (mysqli_num_rows($result) == 0) quit();
$row = mysqli_fetch_assoc($result);

echo $row['vi'];

function quit(){
	echo ""; 
	exit;
}