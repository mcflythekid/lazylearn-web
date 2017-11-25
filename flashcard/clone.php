<?php
	session_start();
	
	require_once("../private/config.php"); 
	require_once("../private/lib.php");
	require_once("../private/remember.php");
	
	if (!isset($_GET["id"])){
		echo "Not Found";
		die();
	}
	$id = $_GET["id"];
	
	if (!isset($_SESSION["username"])){
		header("Location: /login.php?redirect=/flashcard/clone.php?id=" . $id);
		die();
	}
	
	$con = open_con();
	
	$stmt = mysqli_prepare($con, "SELECT id, name, public, cards, category, username FROM sets WHERE id = ?;");
	mysqli_stmt_bind_param($stmt, "i", $id);
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);
	if (mysqli_num_rows($result) > 0) {
		$set = mysqli_fetch_assoc($result); 
	}else{
		echo "Not Found";
		mysqli_stmt_close($stmt);
		mysqli_close($con);
		die();
	}
	mysqli_stmt_close($stmt);
	
	if ($_SESSION["username"] === $set["username"]){
		echo "Forbidden";
		mysqli_close($con);
		die();
	}
	
	if ($set["public"] == 0){
		echo "Forbidden";
		mysqli_close($con);
		die();
	}
	
	$url = strtolower($set["name"]);
	$url = trim($url);
	$url = preg_replace('/đ/', 'd', $url);
	$url = iconv('UTF-8', 'ASCII//TRANSLIT', $url);
	$url = preg_replace('/[^a-z0-9]/', '-', $url);
	$url = preg_replace('/[\-]+/', '-', $url);
	$url = trim($url, '-');
	if (strlen($url) > 0) { $url.= "-"; }
	
	$stmt = mysqli_prepare($con, "INSERT INTO sets (name, url, category, username, cards) VALUES (?, ?, ? , ? , ?);");
	mysqli_stmt_bind_param($stmt, "ssssi", $set["name"], $url, $set["category"], $_SESSION["username"], $set["cards"]);
	if (!mysqli_stmt_execute($stmt)){
		mysqli_stmt_close($stmt);
		echo "Internal Server Error";
		die();
	}
	mysqli_stmt_close($stmt);
	$new_set_id = mysqli_insert_id($con);
	
	$stmt = mysqli_prepare($con, "INSERT INTO searches (id, name) value (?,?);");
	mysqli_stmt_bind_param($stmt, "is", $new_set_id, strtolower($set["name"]));
	mysqli_stmt_execute($stmt);
	
	$random_name = "tmp_" . substr( md5(rand()), 0, 7);
	
	$stmt = mysqli_prepare($con, sprintf("CREATE TEMPORARY TABLE %s (SELECT front, back, ? AS username, ? AS set_id FROM cards WHERE set_id = ?);", $random_name));
	mysqli_stmt_bind_param($stmt, "sii", $_SESSION["username"], $new_set_id, $set["id"]);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_close($stmt);
		
	$stmt = mysqli_prepare($con, sprintf("INSERT INTO cards (front, back, username, set_id)  SELECT * FROM %s;", $random_name));
	mysqli_stmt_execute($stmt);
	mysqli_stmt_close($stmt);
	
	$stmt = mysqli_prepare($con, sprintf("DROP TABLE %s;", $random_name));
	mysqli_stmt_execute($stmt);
	mysqli_stmt_close($stmt);
	
	mysqli_close($con);
	header("Location: /flashcard/view.php?id=" . $new_set_id);
	die();
?>