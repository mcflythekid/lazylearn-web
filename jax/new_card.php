<?php 
	session_start();
	require_once("../private/config.php"); 
	require_once("../private/lib.php");
	
	/* Accept post and authenticated user */
	if ($_SERVER['REQUEST_METHOD'] !== "POST" || !isset($_SESSION["username"])){
		die();
	}
	
	/* Get data */
	if (!isset($_POST["set_id"])){
		die();
	}
	if (!isset($_POST["front"])){
		die();
	}
	if (!isset($_POST["back"])){
		die();
	}
	$set_id = $_POST["set_id"];
	$front = trim($_POST["front"]);
	$back = trim($_POST["back"]);
	if (empty($front) || empty($back) || strlen($front) > 1024 ||  strlen($back) > 1024){
		die();
	}
	
	/* Open database */
	$con = open_con();
	
	/* Check username */
	$stmt = mysqli_prepare($con, "SELECT username FROM sets WHERE id = ?;");
	mysqli_stmt_bind_param($stmt, "i", $set_id);
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);
	if (mysqli_num_rows($result) > 0) {
		$set = mysqli_fetch_assoc($result);
		if ($_SESSION["username"] !== $set["username"]){
			mysqli_stmt_close($stmt);
			mysqli_close($con);
			die();
		}
	}else{
		die();
	}
	
	/* Change step */
	$stmt = mysqli_prepare($con, "INSERT INTO cards (username, front, back, set_id) VALUES(?,?,?,?);");
	mysqli_stmt_bind_param($stmt, "sssi", $_SESSION["username"], $front, $back, $set_id);
	mysqli_stmt_execute($stmt);
	$new_id = mysqli_insert_id($con);
	$stmt = mysqli_prepare($con, "UPDATE sets SET cards = cards + 1 WHERE id = ?;");
	mysqli_stmt_bind_param($stmt, "i", $set_id);
	mysqli_stmt_execute($stmt);
	mysqli_close($con);
	echo $new_id;
	

?>