<?php 
	session_start();
	require_once("../private/config.php"); 
	require_once("../private/lib.php");
	
	/* Accept post and authenticated user */
	if ($_SERVER['REQUEST_METHOD'] !== "POST" || !isset($_SESSION["username"]) || !isset($_POST["email"])){
		echo "Fuck you!!!";
		die();
	}
	

	if (!empty($_POST["email"])){
		$email = filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL, FILTER_NULL_ON_FAILURE );
		if (is_null($email)){
			echo -1;
			die();
		}
	}
	
	/* Open database */
	$con = open_con();
	
	if (empty($email)){
		$stmt = mysqli_prepare($con, "UPDATE users SET email = NULL  WHERE username = ?;");
		mysqli_stmt_bind_param($stmt, "s", $_SESSION["username"]);
		if (!mysqli_stmt_execute($stmt)){
			mysqli_close($con);
			echo 0;
			die();
		}
		mysqli_close($con);
		echo 2;
	}else{
		$stmt = mysqli_prepare($con, "UPDATE users SET email = ?  WHERE username = ?;");
		mysqli_stmt_bind_param($stmt, "ss", $email, $_SESSION["username"]);
		if (!mysqli_stmt_execute($stmt)){
			mysqli_close($con);
			echo 0;
			die();
		}
		mysqli_close($con);
		echo 1;
	}
?>