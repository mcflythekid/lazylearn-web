<?php
	session_start();
	header('Content-Type: application/json');
	require_once("../private/config.php"); 
	require_once("../private/lib.php");
	require_once("../private/remember.php");
	
	if (!isset($_GET["id"])){
		header("Location: /");
		die();
	}
	$id = $_GET["id"];
	$limit = isset($_GET["limit"])? $_GET["limit"] : 100;
	$offset = isset($_GET["offset"])? $_GET["offset"] : 0;
	
	$con = open_con();
	
	$stmt = mysqli_prepare($con, "SELECT id, name, public, cards, category, username, UNIX_TIMESTAMP(created) AS created, url FROM sets WHERE id = ? LIMIT ? OFFSET ?");
	mysqli_stmt_bind_param($stmt, "iii", $id, $limit, $offset);
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);
	if (mysqli_num_rows($result) > 0) {
		$set = mysqli_fetch_assoc($result); 
	}else{
		mysqli_close($con);
		//header("Location: /");
		die();
	}
	mysqli_stmt_close($stmt);
	
	if ($set["public"] == 0 && (!isset($_SESSION["username"]) || $_SESSION["username"] !== $set["username"])){
		mysqli_close($con);
		header("Location: /");
		die();  
	}
	
	$stmt = mysqli_prepare($con, "SELECT id, front, back FROM cards WHERE set_id = ?;");
	mysqli_stmt_bind_param($stmt, "i", $id);
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);
	if (mysqli_num_rows($result) > 0) {
		$cards = array();
		while($row = mysqli_fetch_assoc($result)) {
			$cards[] = $row;
		}
	}
	mysqli_stmt_close($stmt);
	
	mysqli_close($con);
	
	$is_authenticated = isset($_SESSION["username"]);
	if ($is_authenticated){
		$is_owner = $_SESSION["username"] === $set["username"];
	}else{
		$is_owner = false;
	}
	echo json_encode(
	[
	"rows" => $cards
	]);
?>