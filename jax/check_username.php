<?php 
	require_once("../private/config.php"); 
	require_once("../private/lib.php");
	
	if ($_SERVER['REQUEST_METHOD'] !== "POST" || !isset($_POST["username"])  ){
		echo "0";
		die();
	}
	
	if (!is_valid_username($_POST["username"])){
		echo "0";
		die();
	}
	
	$con = open_con();
	$stmt = mysqli_prepare($con, "SELECT COUNT(*) AS number FROM users WHERE username = ?;");
	mysqli_stmt_bind_param($stmt, "s", $_POST["username"]);
	mysqli_stmt_execute($stmt);
	
	$result = mysqli_stmt_get_result($stmt);
	$row = mysqli_fetch_assoc($result);
	
	mysqli_close($con);
	
	if ($row["number"] == 0){
		echo "1";
	}else{
		echo "0";
	}

?>