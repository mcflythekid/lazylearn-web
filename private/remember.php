<?php 
	
	// language require
	if (isset($_SESSION["lang"])){
		require_once(dirname(__DIR__) . "/lang/" . $_SESSION["lang"] . ".php");
	} else if (isset($_SESSION["lang"])){
		require_once(dirname(__DIR__) . "/lang/" . $_COOKIE["lang"] . ".php");
	} else {
		require_once(dirname(__DIR__) . "/lang/en.php");
	}

	/* Perform checking for guest with cookie  */
	if (!isset($_SESSION["username"])  &&   isset($_COOKIE["remember_me"])   ){
		
		/* Get COOKIE_SERIES and COOKIE_TOKEN */
		$strings = explode(":", $_COOKIE["remember_me"]);
		$series = $strings[0];
		$token = $strings[1];
				
		/* Continue if have data */
		if (isset($series) && isset($token)){
		
			/* Open database */
			$con = open_con();
			$stmt = mysqli_prepare($con, "SELECT username, token, UNIX_TIMESTAMP(last_used) AS last_used FROM remembers WHERE series = ?;");
			mysqli_stmt_bind_param($stmt, "s", $series);
			mysqli_stmt_execute($stmt);
			$result = mysqli_stmt_get_result($stmt);
			
			/* Have data */
			if (mysqli_num_rows($result) > 0) {
				$row = mysqli_fetch_assoc($result);
				
				/* Get hashed token */
				$hashed = $row["token"]; 
				
				/* Get time */
				$not_expired = time() - ($REMEMBER_ME_DAY * 86400) < $row["last_used"];			
								
				/* Check token */
				if (md5($token . $SALT) === $hashed && $not_expired){
										
					/* Set session */
					$_SESSION["username"] = $row["username"];
					
					/* Set cookie */
					$token = md5(openssl_random_pseudo_bytes(30) . $SALT);
					$a = setcookie("remember_me", $series . ":" . $token, time() + (86400 * $REMEMBER_ME_DAY));
					
					/* Set record in database */
					$token = md5($token . $SALT);
					$stmt = mysqli_prepare($con, "UPDATE remembers SET token = ? WHERE series = ?;");
					mysqli_stmt_bind_param($stmt, "ss", $token, $series);
					mysqli_stmt_execute($stmt);
						
				} else{

					/* Delete from database */
					$stmt = mysqli_prepare($con, "DELETE FROM remembers WHERE series = ?;");
					mysqli_stmt_bind_param($stmt, "s", $series);
					mysqli_stmt_execute($stmt);
					
					/* Delete from cookie */
					setcookie("remember_me", "", time() - 3600);
					
				}
				
			}else{
				
				/* Delete from cookie */
				setcookie("remember_me", "", time() - 3600);
			}
			
			/* Close database */
			mysqli_close($con);
			
		}
	}
?>