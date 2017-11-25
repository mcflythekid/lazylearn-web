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
	

	$data = array();
	
	/* Now left (Expired) */
	$stmt = mysqli_prepare($con, "SELECT COUNT(*) AS data FROM cards WHERE username = ? AND step = 0 AND weakup IS NULL;");
	mysqli_stmt_bind_param($stmt, "s", $username);
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);
	$row = mysqli_fetch_assoc($result);
	$data[0][0] = $row["data"];
	mysqli_stmt_close($stmt);
	
	/* Now right */
	$stmt = mysqli_prepare($con, "SELECT COUNT(*) AS data FROM cards WHERE username = ? AND step = 0 AND weakup IS NOT NULL;");
	mysqli_stmt_bind_param($stmt, "s", $username);
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);
	$row = mysqli_fetch_assoc($result);
	$data[0][1] = $row["data"];
	mysqli_stmt_close($stmt);
	
	
	
	for ($i = 1; $i <= 4; $i++) {
		
		/* Left (Expired) */
		$stmt = mysqli_prepare($con, "SELECT COUNT(*) AS data FROM cards WHERE username = ? AND step = ? AND weakup <= NOW();");
		mysqli_stmt_bind_param($stmt, "si", $username, $i);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		$row = mysqli_fetch_assoc($result);
		$data[$i][0] = $row["data"];
		mysqli_stmt_close($stmt);
		
		/* Right */
		$stmt = mysqli_prepare($con, "SELECT COUNT(*) AS data FROM cards WHERE username = ? AND step = ?;");
		mysqli_stmt_bind_param($stmt, "si", $username, $i);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		$row = mysqli_fetch_assoc($result);
		$data[$i][1] = $row["data"] - $data[$i][0];
		mysqli_stmt_close($stmt);
	
	}
	
	/* Get sets data */
	if (!isset($_GET["cat"])){
		$stmt = mysqli_prepare($con, "SELECT id, name, public, cards, UNIX_TIMESTAMP(created) AS created, url, category FROM sets WHERE username = ? ORDER BY created DESC;");
		mysqli_stmt_bind_param($stmt, "s", $username);
	} else {
		$stmt = mysqli_prepare($con, "SELECT id, name, public, cards, UNIX_TIMESTAMP(created) AS created, url, category FROM sets WHERE username = ? AND category = ? ORDER BY created DESC;");
		mysqli_stmt_bind_param($stmt, "ss", $username, $_GET["cat"]);
	}
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
<div id="main">




<h1 id="username"><?php=noHtml( $_GET["id"]) ?></h1>

<!-- Graph -->
<div id="vertgraph-father">
	<div id="vertgraph">
		<div id="legend">
			<div id="legendleft"><?=$lang["user"]["today"]?>: <span class="green"><?php echo ($data[0][0] + $data[1][0] + $data[2][0] + $data[3][0] + $data[4][0] + $data[0][1]);?></span></div>
			<div id="legendright">
			<?=$lang["user"]["expired"]?>: <span class="legsp" id="expired"><?php echo ($data[0][0] + $data[1][0] + $data[2][0] + $data[3][0] + $data[4][0]);?></span>
			<?=$lang["user"]["correct"]?>: <span class="legsp" id="correct"><?php echo ($data[1][1] + $data[2][1] + $data[3][1] + $data[4][1]);?></span>
			<?=$lang["user"]["incorrect"]?>: <span class="legsp" id="incorrect"><?php echo $data[0][1];?></span></div>
		</div>

		<dl>
			<dt class="a00"><?=$lang["user"]["now"]?></dt>
			<dt class="a10">1 <?=$lang["user"]["day"]?></dt>
			<dt class="a20">3 <?=$lang["user"]["days"]?></dt>
			<dt class="a30">1 <?=$lang["user"]["week"]?></dt>
			<dt class="a40">1 <?=$lang["user"]["month"]?></dt>
						
			<?php
				for ($i = 0; $i <= 1; $i++){
					for ($j = 0; $j <= 4; $j++){
						
						$card_number = $data[$j][$i];
						
						$background_css_class = "a" . $j . $i;
						if ($card_number == 0){
							$data_css_class = $background_css_class . "-blank";
						} else  {
							$data_css_class = $background_css_class;
						}
						
						$height = floor($card_number / 3) + 1;
						
						if ($height > 110){
							$height = 110;
						}
			?>
			<dd class="<?php echo $background_css_class; ?> background" title="<?php echo $card_number;?> <?=$lang["user"]["cards"]?>"></dd>
			<dd class="<?php echo $data_css_class; ?>" style="height: <?php echo $height;?>px;" title="<?php echo $card_number;?> <?=$lang["user"]["cards"]?>">
				<?php if ($card_number >= 50){ echo $card_number; }?>
			</dd>
			<?php } } ?>				

		</dl>
	</div>
</div>
<!-- End graph -->

<!-- Categories -->
<div id="ucs-taglist" class="box">
	<h2 id="flashcard_category"><?=$lang["user"]["categories"]?></h2>

	<a class="taglink" href="/user/<?=$username?>#flashcard_category"><strong>All Flashcards</strong></a>&nbsp;&nbsp;
	
	<?php  if (!empty($categories) ){ foreach($categories as $category){ ?>
	<!--<a class="taglink" href="/user/<?php echo $username; ?>/<?php echo noHTML($category); ?>"><?php echo noHTML($category); ?></a>&nbsp;&nbsp;-->
	<a class="taglink" href="/user/<?=$username?>?cat=<?=noHTML($category)?>#flashcard_category"><?php echo noHTML($category); ?></a>&nbsp;&nbsp;
	<?php } } else { echo '<i>' . $lang["user"]["no_data"] . '</i>'; } ?>

</div>
<!-- End categories -->


<!-- Sets -->
<style>
.taglink.taglink2{
	line-height:1em !important;
}
span.private_set{
	color: #cc3334;
}
a{
	text-decoration: none !important;
}
</style>
<div class="box">
	<h2>
	<?=isset($_GET["cat"]) ? noHtml($_GET["cat"]) : ""?>
	<?=$lang["user"]["flashcards"]?>
	</h2>

	<table><tbody>
		<?php  if(isset($sets)){ foreach ($sets as $set){ ?>
		<tr><td>
		
			<span class="cardsetlist_name">
			
				<?php $set_css_class = $set["public"] == 0 ? "private_set" : ""?>
			
				<a href="/flashcard/<?php echo $set["url"]; ?><?php echo $set["id"]; ?>">
					<span class="<?=$set_css_class?>"><?=noHtml($set["name"])?></span>
					<span dir="ltr"><small>(<?=$set["cards"]?> <?=$lang["user"]["cards"]?>)</small></span>
				</a>
				
				<!--<?php if ($set["public"] == 0){ ?><img src="<?php echo $ASSET; ?>/img/lock.gif" class="lock"><?php } ?>-->
				
				<?php if ($set["category"] != "" && !isset($_GET["cat"])){ ?>
				<a class="taglink taglink2" href="/user/<?php echo $username; ?>/<?=noHTML($set["category"]); ?>"><?= noHTML($set["category"]); ?></a>
				<?php } ?>
				
			</span>
			
			<!--<div class="cardsetlist_details"><?=$lang["user"]["created"]?> <?php echo timeAgo($set["created"]);?></div>-->
		</td></tr>
		<?php } }else{ echo "<i>No data</i>"; } ?>
						
	</tbody></table>
				
</div>
<!-- End sets -->




</div>
</div>
<?php require_once("./private/footer.php"); ?>
</body>
</html>