<?php

	/* Enable session */
	session_start();
	
	/* Include lib */
	require_once("./private/config.php"); 
	require_once("./private/lib.php");
	require_once("./private/remember.php");
	
	/* Go to home if already logged in */
	if (isset($_SESSION["username"])){
		header("Location: /");
		die();
	}
	
	/* Get set id */
	if (!isset($_GET["id"])){
		echo "Not Found";
		die();
	}
	$id = $_GET["id"];
	
	$con = open_con();
	$stmt = mysqli_prepare($con, "SELECT username, UNIX_TIMESTAMP(created) AS created FROM forgets WHERE id = ? ;");
	mysqli_stmt_bind_param($stmt, "s", $id);
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);
	if (mysqli_num_rows($result) == 0) {
		mysqli_close($con);
		echo "Not Found";
		die();
	}else{
		$data = mysqli_fetch_assoc($result); 
		$not_expired = time() - ($FORGET_PASSWORD_DAY * 86400) < $data["created"];		
		if (!$not_expired){
			$stmt = mysqli_prepare($con, "DELETE FROM forgets WHERE id = ?");
			mysqli_stmt_bind_param($stmt, "s", $id);
			mysqli_stmt_execute($stmt);
			mysqli_close($con);
			echo "Forbiden";
			die();
		}
	}
	
	
	/* Flags */
	$try = false;
	
	/* Process POST request */
	if ($_SERVER['REQUEST_METHOD'] === "POST"){
	
		/* Get password */
		$password = $_POST["password"];
		$password2 = $_POST["password2"];
	
		/* Check username and password */
		if ( preg_match("/^(.{8,254})$/", $password) && ($password === $password2)){
			$username = $data["username"];
			$password = md5($username . $password . $SALT);
			$stmt = mysqli_prepare($con, "UPDATE users SET password = ? WHERE username = ?");
			mysqli_stmt_bind_param($stmt, "ss", $password, $username);
			mysqli_stmt_execute($stmt);
			$stmt = mysqli_prepare($con, "DELETE FROM forgets WHERE id = ?");
			mysqli_stmt_bind_param($stmt, "s", $id);
			mysqli_stmt_execute($stmt);
			mysqli_close($con);
			header("Location: /login.php");
			die();
		}else{
			$try = true;
		}
	}
	
	mysqli_close($con);
?>
<!DOCTYPE html>
<html>
<head>
<title>Reset Password - Internet Flashcard Database</title>
<link rel="shortcut icon" href="/favicon.ico"  />
<link rel="stylesheet" href="<?php echo $ASSET?>/css/style.css">
<meta name="robots" content="noindex">
</head>
<body>
<div id="wrapper">
<?php require_once("./private/navbar.php"); ?>
<div id="main">




<?php if ($try){  ?>
	<div id="warning">Please check your input and try again.</div>
<?php } ?>

<div id="recovery_reset" class="greybox">
<h1>Reset Password</h1>
	<form method="post" id="form">
		<p><label for="password">New password:</label><br><input name="password" size="30" type="password"/><br><span class="error" id="error_password"></span></p>
		<p><label for="password2">Confirm new password:</label><br><input name="password2" size="30" type="password"/><br><span class="error" id="error_password2"></span></p>
		<p><input type="submit" value="Submit" class="big_button"/></p>
	</form>
</div>




</div>
</div>
<?php require_once("./private/footer.php"); ?>
</body>
</html>