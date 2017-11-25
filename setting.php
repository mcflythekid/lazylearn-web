<?php
	session_start();
	
	require_once("./private/config.php"); 
	require_once("./private/lib.php");
	require_once("./private/remember.php");
	
	/* Go home if logged in */
	if (!isset($_SESSION["username"])){
		header("Location: /login.php?redirect=/setting.php");
		die();
	}

	$con = open_con();
	$stmt = mysqli_prepare($con, "SELECT email, lang FROM users WHERE username = ? ;");
	mysqli_stmt_bind_param($stmt, "s", $_SESSION["username"]);
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);
	$row = mysqli_fetch_assoc($result);
	$old_email = $row["email"];
	
	/* Flag */
	$try = false;
	
	/* Process POST request */
	if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST["password1"])  ){
	
		/* Get password */
		$password = mysqli_real_escape_string($con, $_POST["password1"]);
	
		/* Check username and password */
		if (!empty($password) && preg_match("/^(.{8,254})$/", $password)){

			//$con = open_con(); remove it
			
			if (!empty($password)){
				
				$password = md5($_SESSION["username"] . $password . $SALT);
				$stmt = mysqli_prepare($con, "UPDATE users SET password = ? WHERE username = ?");
				mysqli_stmt_bind_param($stmt, "ss", $password, $_SESSION["username"]);
				mysqli_stmt_execute($stmt);
				
				/* Delete remember me data */
				if (isset($_COOKIE["remember_me"])){
					/* Get cookie data */
					$strings = explode(":", $_COOKIE["remember_me"]);
					$series = $strings[0];
					/* Valid data */
					if (isset($series)){
						/* Delete from database */
						$stmt = mysqli_prepare($con, "DELETE FROM remembers WHERE series = ?;");
						mysqli_stmt_bind_param($stmt, "s", $series);
						mysqli_stmt_execute($stmt);
					}
					/* Delete from cookie */
					setcookie("remember_me", "", time() - 3600);
				}
				
				mysqli_close($con);
				unset($_SESSION);
				session_destroy();
				header("Location: /login.php");
				die();
			}
			
		}else{
			$try = true;
		}
	} else if  ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST["lang"])  ){
		
		/* Get language */
		$lang = mysqli_real_escape_string($con, $_POST["lang"]);

		// update sql
		$stmt = mysqli_prepare($con, "UPDATE users SET lang = ? WHERE username = ?");
		mysqli_stmt_bind_param($stmt, "ss", $lang, $_SESSION["username"]);
		mysqli_stmt_execute($stmt);
		
		// update UI
		$row["lang"] = $lang;
		
		// update session_cache_expire
		$_SESSION["lang"] = $lang;
		
		// update cookie
		setcookie("lang", $lang, 2147483647);
	}
	
	mysqli_close($con);
?>
<!DOCTYPE html>
<html>
<head>
<title>Settings</title>
<link rel="shortcut icon" href="/favicon.ico"  />
<link rel="stylesheet" href="<?php echo $ASSET?>/css/style.css">
</head>
<body>
<div id="wrapper">
<?php require_once("./private/navbar.php"); ?>
<div id="main">

<?php if ($try){  ?>
	<div id="warning">Please check your input and try again.</div>
<?php } ?>

	<div class="greybox setting">
	<h1>Change your email address</h1>
			<p>
				Curent: <a id="new_email" style="opacity: 0.5;"><?php if (isset($old_email))echo noHTML($old_email); else echo "Not set"; ?></a><br>
			</p>
			<p>
				<label for="email"><strong>Email</strong></label><br><input name="email" size="30" value="<?php echo $old_email; ?>"/>
				<input class="big_button" id="change_email" type="submit" value="Change"/>
				<br>
				<span id="error_email"></span>
			</p>
	</div>
	
	<div class="greybox setting">
	<h1>Change your password</h1>
		<form method="post" id="form">
			<p><label for="password1"><strong>New Password</strong></label><br><input name="password1" size="30" type="password"/><br><span class="error" id="error_password1"></span></p>
			<p><label for="password2"><strong>Confirm New Password</strong></label><br><input name="password2" size="30" type="password"/><br><span class="error" id="error_password2"></span></p>
			<p><input class="big_button" type="submit" value="Change"/></p>
		</form>
	
	</div>
	
	<div class="greybox setting">
	<h1>Change your language</h1>
		<form method="post" id="form">
			<p>
				<select name="lang">
				  <option value="vi" <?= $row["lang"] === "vi" ? "selected" : ""?>>Tiếng Việt</option>
				  <option value="en" <?= $row["lang"] === "en" ? "selected" : ""?>>English</option>
				</select>
			</p>

			<p><input class="big_button" type="submit" value="Change"/></p>
		</form>
	
	</div>
			

</div>
</div>
<?php require_once("./private/footer.php"); ?>
<script>
	var old_email = "<?php echo $old_email; ?>";
</script>
<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
<script src="<?php echo $ASSET; ?>/js/setting.js"></script>
</body>
</html>