<?php
	session_start();
	require_once("./private/config.php"); 
	require_once("./private/lib.php");
	require_once("./private/remember.php");
	

	if (!isset($_GET["id"])){
		header("Location: /");
		die();
	}
	$username = $_GET["id"];
	
	$con = open_con();
	
	$stmt = mysqli_prepare($con, "SELECT COUNT(*) AS itok FROM users WHERE username = ?;");
	mysqli_stmt_bind_param($stmt, "s", $username);
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);
	$row = mysqli_fetch_assoc($result);
	if ($row["itok"] == 0){
		mysqli_stmt_close($stmt);
		mysqli_close($con);
		session_destroy();
		echo "Not Found";
		die();
	}
	mysqli_stmt_close($stmt);
	
	/* Get sets data */
	$stmt = mysqli_prepare($con, "SELECT
	 s.id, s.name, s.public, s.cards, UNIX_TIMESTAMP(s.created) AS created, s.url, s.category, COUNT(c.id) as repetition
     FROM sets s
	 LEFT JOIN cards c ON s.id = c.set_id AND c.step <= ? AND (c.weakup <= NOW() OR c.weakup IS NULL)
	 WHERE s.username = ?
	 GROUP BY s.id, s.name, s.public, s.cards, s.created, s.url, s.category 
	 ORDER BY s.category ASC, s.name ASC;");
	mysqli_stmt_bind_param($stmt, "is", $LEITNER_SIZE, $username);
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);
	if (mysqli_num_rows($result) > 0) {
		
		/* Create array */
		$sets = array();
		
		while($row = mysqli_fetch_assoc($result)) {
			$sets[] = $row;
		}

	}
	mysqli_stmt_close($stmt);
	
	/* Get time up data */
	$stmt = mysqli_prepare($con, "SELECT COUNT(c.id) AS timeup
     FROM sets s
	 LEFT JOIN cards c ON s.id = c.set_id AND c.step <= ? AND (c.weakup <= NOW() OR c.weakup IS NULL)
	 WHERE s.username = ?
	 GROUP BY s.id
	 ORDER BY created DESC;");
	mysqli_stmt_bind_param($stmt, "is", $LEITNER_SIZE, $username);
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);
	if (mysqli_num_rows($result) > 0) {
		/* Create array */
		$sets_timeup = array();
		while($row = mysqli_fetch_assoc($result)) {
			$sets_timeup[] = $row;
		}
	}
	mysqli_stmt_close($stmt);
		
	/* Get categories */
	$stmt = mysqli_prepare($con, "SELECT DISTINCT(category) AS name FROM sets WHERE username = ?;");
	mysqli_stmt_bind_param($stmt, "s", $username);
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);
	if (mysqli_num_rows($result) > 0) {
		
		$categories = array();
		
		while($row = mysqli_fetch_assoc($result)) {
			if ($row["name"] !== ""){
				$categories[] = $row["name"];
			}
		}

	}
	mysqli_stmt_close($stmt);
	
	mysqli_close($con);
	
?>
<!DOCTYPE html>
<html>
<head>
<title>
	<?=isset($_GET["cat"]) ? noHtml($_GET["cat"]) . " Flashcards | " : ""?>
	<?php echo $username; ?>
</title>
<link rel="shortcut icon" href="/favicon.ico"  />
<link rel="stylesheet" href="<?php echo $ASSET?>/css/style.css">
<meta name="description" content="Flashcards of <?php echo $username; ?>" />
<meta name="canonical" content="https://lazylearn.com/user/<?php echo $username; ?>"/>





</head>
<body>
<div id="wrapper">
<?php require_once("./private/navbar.php"); ?>
<?php require_once("./private/graph.php"); ?>
<div id="main">

<h1 id="username"><?=noHtml( $_GET["id"]) ?></h1>

<?php graph($username); ?>

<div class="box">
	<h2><?=$lang["user"]["flashcards"]?></h2>

	<table><tbody>
		<?php  if(isset($sets)){ for ($i = 0; $i < sizeof($sets); $i++){ $set = $sets[$i];?>
		<tr><td>
		
			<span class="cardsetlist_name">
				

				<?php if ($set["public"] == 0){ ?>
					<img src="<?php echo $ASSET; ?>/img/lock.gif" class="lock" alt="Private" title="Private">
				<?php } else {?>
					<img src="<?php echo $ASSET; ?>/img/world.png" class="lock" alt="Public" title="Public">
				<?php } ?>
								
				<?php if ($set["repetition"] > 0){ ?>
					<img src="<?php echo $ASSET; ?>/img/leitner_system_icon.png" class="lock" alt="Available for study" title="Available for study">
				<?php } ?>
				
			
				
				<span class="card_count"><?=$set["cards"]?> <?=$lang["user"]["cards"]?></span>
				
				<?php if ($set["category"] != "" ){ ?>
					<a class="set_category" href="/flashcard/category.php?id=<?=noHTML($set["category"])?>">
						<?=$set["category"]?>
					</a>
				<?php } ?>
				
				
				<a href="/flashcard/<?php echo $set["url"]; ?><?php echo $set["id"]; ?>">
					<span><?=noHtml($set["name"])?></span>
				</a>

			</span>
			
		</td></tr>
		<?php } }else{ echo "<i></i>"; } ?>
						
	</tbody></table>
		
</div>
<!-- End sets -->




</div>
</div>
<?php require_once("./private/footer.php"); ?>
</body>
</html>