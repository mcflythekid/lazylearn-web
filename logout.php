<?php
	session_start();
	require_once("./private/config.php");
	require_once("./private/lib.php");
	
	/* Accept POST and authenticated user */
	if ($_SERVER['REQUEST_METHOD'] != "POST" || !isset($_SESSION["username"])){
		header("Location: /");
		die();
	}
	
	/* Delete remember me data */
	if (isset($_COOKIE["remember_me"])){
		
		/* Get cookie data */
		$strings = explode(":", $_COOKIE["remember_me"]);
		$series = $strings[0];
		
		/* Valid data */
		if (isset($series)){
		
			/* Delete from database */
			$con = open_con();
			$stmt = mysqli_prepare($con, "DELETE FROM remembers WHERE series = ?;");
			mysqli_stmt_bind_param($stmt, "s", $series);
			mysqli_stmt_execute($stmt);
			mysqli_close($con);
			
		}
		
		/* Delete from cookie */
		setcookie("remember_me", "", time() - 3600);
		
	}
	
	/* Logout */
	unset($_SESSION);
	session_destroy();
	
	/* Go home */
	header("Location: /");
	die();
?>