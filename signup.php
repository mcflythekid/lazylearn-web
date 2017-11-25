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
	
	/* Failed flag */
	$signup_failed = false;
	
	/* Process POST request */
	if ($_SERVER['REQUEST_METHOD'] == "POST"){
	
		/* Get username and password */
		$username = $_POST["username"];
		$password = $_POST["password"];
		
		/* Check username and password */
		if (preg_match("/^([a-z0-9]{3,30})$/", $username) &&  preg_match("/^(.{8,254})$/", $password) && is_valid_username($username)){
		
			/* Hash password */
			$password = md5($username . $password . $SALT);
			
			/* Open database and query */
			$con = open_con();
			$stmt = mysqli_prepare($con, "INSERT INTO users (username,password,ip) value (?,?,?);");
			mysqli_stmt_bind_param($stmt, "sss", $username, $password, $_SERVER['REMOTE_ADDR']);
			
			/* Success */
			if (mysqli_stmt_execute($stmt)){
				/* Set username to session */
				$_SESSION["username"] = $username;
				
				if (isset($_GET["redirect"])){
					header("Location: " . $_GET["redirect"]);
				} else {
					/* Redirect to home */
					header("Location: /user.php?username=" . $username);
				}
				die();
			}else{
				$signup_failed = true;
			}
			
			/* Close connection */
			mysqli_close($con);
			
		}else{
			$signup_failed = true;
		}
		
	}
	
?>
<!DOCTYPE html>
<html>
<head>
<title>Sign Up for a Lazylearn Account</title>
<link rel="shortcut icon" href="/favicon.ico"  />
<link rel="stylesheet" href="<?php echo $ASSET?>/css/style.css">
<meta name="description" content="Welcome to Lazylearn, log in to learn now!" />
<meta name="canonical" content="https://lazylearn.com/signup"/>
</head>
<body>
<div id="wrapper">
<?php require_once("./private/navbar.php"); ?>
<div id="main">
	
	
	
	
<!-- This is a grey box which contain signup form -->
<div class="greybox" id="user_new">

	<h1>Sign Up</h1>
	
	<form method="post" id="form">
		<p>
			<label for="username"><strong>Username</strong></label><br>
			<input id="username" name="username" size="30" value="<?php if (isset($_POST['username'])) {echo $_POST['username'];} ?>"/>
			<br>
			<span class="error" id="error_username"></span>
		</p>
		<p>
			<label for="password"><strong>Password</strong></label><br>
			<input id="password" name="password" size="30" type="password"/>
			<br>
			<span class="error" id="error_password"></span>
		</p>
		<p>
			<label for="password2"><strong>Confirm Password</strong></label><br>
			<input id="password2" name="password2" size="30" type="password"/>
			<br>
			<span class="error" id="error_password2"></span>
		</p>
		<p><input class="big_button" type="submit" value="Sign Up" /></p>
	</form>
	
	 <a href="/login<?php if (isset($_GET["redirect"])){echo "?redirect=" . noHTML($_GET["redirect"]);} ?>">Log In?</a>
	
</div>




</div>	
</div>
<?php require_once("./private/footer.php"); ?>
<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
<script src="<?php echo $ASSET; ?>/js/signup.js"></script>
<?php if ($signup_failed){  ?><script>check_username();</script><?php } ?>
</body>
</html>