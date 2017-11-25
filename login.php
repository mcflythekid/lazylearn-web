<?php
session_start();
require_once("./private/config.php"); 
require_once("./private/lib.php");
require_once("./private/remember.php");

//echo $_POST["username"] . "/" .  $_POST["password"]. "<br>";
//echo var_export($_GET) . "<br>";
//echo var_export($_POST). "<br>";
//exit;

/* Go home if logged in */
if (isset($_SESSION["username"])){
	header("Location: /");
	die();
}

/* Remember me check box */
if (!isset($_COOKIE["remember_me_checked"])){
	setcookie("remember_me_checked", "off", 2147483647);
	$remember_me_checked = false;
}else {/* If don't have cookie */
	if ( $_COOKIE["remember_me_checked"] == "on"){
		$remember_me_checked = true;
	}else{
		$remember_me_checked = false;
	}
}

/* Flag */
$login_failed = false;

/* Process POST request */
if ($_SERVER['REQUEST_METHOD'] == "POST"){
	
	/* Open database */
	$con = open_con();
	$stmt = mysqli_prepare($con, "SELECT password, lang FROM users WHERE username = ?;");
	mysqli_stmt_bind_param($stmt, "s", $_POST["username"]);
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);
	
	/* Have data */
	if (mysqli_num_rows($result) > 0) {
		$row = mysqli_fetch_assoc($result);
		
		/* Hashed pasword */
		$hashed = $row["password"];
		
		/* Password matched */
		if (md5($_POST["username"] . $_POST["password"] . $SALT) === $hashed){
			
			/* Remember me check box */
			if (isset($_POST["remember_me"]) && $_POST["remember_me"] == "on"){
				
				/* Create new series and token */
				$series = md5(openssl_random_pseudo_bytes(30) . $SALT);
				$token = md5(openssl_random_pseudo_bytes(30) . $SALT);
				
				/* Save data to cookie */
				setcookie("remember_me", $series . ":" . $token, time() + (86400 * $REMEMBER_ME_DAY));
				
				/* Save data to database */
				$stmt = mysqli_prepare($con, "INSERT INTO remembers(username, series, token) VALUES (? , ? , ?);");
				$token = md5($token . $SALT);
				mysqli_stmt_bind_param($stmt, "sss", $_POST["username"], $series, $token);
				mysqli_stmt_execute($stmt);
				
				/* Set check box status to cookie */
				setcookie("remember_me_checked", "on", 2147483647);
				
			}else{
				
				/* Set check box status to cookie */
				setcookie("remember_me_checked", "off", 2147483647);
				
			}
			
			/* Set username, language */
			$_SESSION["username"] = $_POST["username"];
			$_SESSION["lang"] = $row["lang"];
			
			/* Close database and go home */
			mysqli_close($con);
			if (isset($_GET["redirect"])){
				header("Location: " . $_GET["redirect"]);
				die();
			}
			header("Location: /user/" . $_SESSION["username"]);
			die();
		}
	}
	
	/* Set failed, close database */
	$login_failed = true;
	mysqli_close($con);
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Log in to your Lazylearn account</title>
<link rel="shortcut icon" href="/favicon.ico"  />
<link rel="stylesheet" href="<?php echo $ASSET?>/css/style.css">
<meta name="description" content="Welcome back to Lazylearn, log in to learn now!" />
<meta name="canonical" content="https://lazylearn.com/login"/>
</head>
<body>
<div id="wrapper">
<?php require_once("./private/navbar.php"); ?>
<div id="main">
	
	<?php if ($login_failed){ ?><div id="warning">Your username or password is invalid.</div><?php } ?>
	<div id="acct_login" class="greybox">
		<h1>Log In</h1>
		<form name="login_form" method="post" id="login_form" >
			<p><label for="username"><strong>Username</strong></label><br><input id="username" name="username" size="30"/></p>
			<p><label for="password"><strong>Password</strong></label><br><input id="password" name="password" size="30" type="password"/></p>
			<p><input name="remember_me" id="remember_me" type="checkbox" <?php if ($remember_me_checked){echo "checked";} ?> ><label class="remember_me" for="remember_me">Remember me on this computer</label></p>
			<p><input class="big_button" value="Log In" type="submit" ></p>
		</form>
		<a href="/signup<?php if (isset($_GET["redirect"])){echo "?redirect=" . noHTML($_GET["redirect"]);} ?>">Sign up now!</a><br><br>
		<a href="/recovery">Forgot your password?</a>
	</div>


	
	
	
</div>
</div>
<?php require_once("./private/footer.php"); ?>
<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
<script src="<?php echo $ASSET; ?>/js/login.js"></script>
</body>
</html>