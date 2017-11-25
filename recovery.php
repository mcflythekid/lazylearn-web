<?php
	session_start();
	
	require_once("./private/config.php"); 
	require_once("./private/lib.php");
	require_once("./private/remember.php");
	
	/* Go home if logged in */
	if (isset($_SESSION["username"])){
		header("Location: /");
		die();
	}
	
	/* Flag */
	$try_again = false;
	$ok = false;
	
	if (isset($_POST["data"]) ){
		$data = $_POST["data"];
		if (!preg_match("/^(.{1,254})$/", $data)){
			$try_again = true;
		}else{
			/* Open database */
			$con = open_con();
			$stmt = mysqli_prepare($con, "SELECT username, email FROM users WHERE email IS NOT NULL AND email <> '' AND (username = ? OR email = ?);");
			mysqli_stmt_bind_param($stmt, "ss", $data, $data);
			mysqli_stmt_execute($stmt);
			$result = mysqli_stmt_get_result($stmt);
			if (mysqli_num_rows($result) == 0) {
				$try_again = true;
			}else{
				$row = mysqli_fetch_assoc($result);
				
				/* data */
				$username = $row["username"];
				$id = md5(openssl_random_pseudo_bytes(30) . $SALT);
				$stmt = mysqli_prepare($con, "INSERT INTO forgets(id, username) VALUES (?, ?);");
				mysqli_stmt_bind_param($stmt, "ss", $id, $username);
				mysqli_stmt_execute($stmt);
				
				/* the message */
				$email = $row["email"];
				$msg = sprintf("Please click here to reset your password <a href='https://lazylearn.com/reset.php?id=%s'>https://lazylearn.com/reset.php?id=%s</a>", $id, $id);
				$msg = wordwrap($msg,70);
				$headers =  'MIME-Version: 1.0' . "\r\n"; 
				$headers .= 'From: No Reply <no-reply@lazylearn.com>' . "\r\n";
				$headers .= 'Content-type: text/html; utf-8' . "\r\n"; 
				$row = mysqli_fetch_assoc($result);
				mail($email,"Reset password",$msg, $headers);
				
				
				
				$ok = true;
			}
			mysqli_close($con);
		}
	}
	
	
?>
<!DOCTYPE html>
<html>
<head>
<title>Forgot Password</title>
<link rel="shortcut icon" href="/favicon.ico"  />
<link rel="stylesheet" href="<?php echo $ASSET?>/css/style.css">
<meta name="description" content="Forget your password. Get your recovery link after submit a form." />
<meta name="canonical" content="https://lazylearn.com/recovery"/>
</head>
<body>
<div id="wrapper">
<?php require_once("./private/navbar.php"); ?>
<div id="main">




<?php if ($ok){  ?>
	<div id="notice">Please check your email, an email has been sent.</div>
<?php } ?>
<?php if ($try_again){  ?>
	<div id="warning">No username or email found.</div>
<?php } ?>


<div id="recovery_reset" class="greybox">
	<h1>Forgot Password</h1>
	<br>
	Enter your username or email address below<br>and instructions to reset your password will<br>be emailed to you
	<br>
	<form method="post">
		<p><label for="data"><strong>Username or Email</strong></label><br>
		<input id="data" name="data" size="30"><br></p>
		<input class="big_button" type="submit" value="Reset Password">
	</form>
</div>




</div>
</div>
<?php require_once("./private/footer.php"); ?>
</body>
</html>