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
	
	
	
	for ($i = 1; $i <= 6; $i++) {
		
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



<style>


#vertgraph dl dd.a00-blank  { left: 24px; background-color: #cbcbcb; background-position: -0px bottom !important; }
#vertgraph dl dd.a00 { left: 24px; background-color: #fad163; background-position: -0px bottom !important; }
#vertgraph dl dt.a00 { left: 14px; background-position: -0px bottom !important; }

#vertgraph dl dd.a10-blank  { left: 201px; background-color: #cbcbcb; background-position: -28px bottom !important; }
#vertgraph dl dd.a10 { left: 201px; background-color: #fad163; background-position: -28px bottom !important; }
#vertgraph dl dt.a10 { left: 191px; background-position: -28px bottom !important; }

#vertgraph dl dd.a20-blank  { left: 378px; background-color: #cbcbcb; background-position: -56px bottom !important; }
#vertgraph dl dd.a20 { left: 378px; background-color: #fad163; background-position: -56px bottom !important; }
#vertgraph dl dt.a20 { left: 368px; background-position: -56px bottom !important; }

#vertgraph dl dd.a30-blank  { left: 555px; background-color: #cbcbcb; background-position: -84px bottom !important; }
#vertgraph dl dd.a30 { left: 555px; background-color: #fad163; background-position: -84px bottom !important; }
#vertgraph dl dt.a30 { left: 545px; background-position: -84px bottom !important; }

#vertgraph dl dd.a40-blank  { left: 732px; background-color: #cbcbcb; background-position: -112px bottom !important; }
#vertgraph dl dd.a40 { left: 732px; background-color: #fad163; background-position: -112px bottom !important; }
#vertgraph dl dt.a40 { left: 722px; background-position: -112px bottom !important; }

#vertgraph dl dd.a50-blank  { left: 732px; background-color: #cbcbcb; background-position: -112px bottom !important; }
#vertgraph dl dd.a50 { left: 732px; background-color: #fad163; background-position: -112px bottom !important; }
#vertgraph dl dt.a50 { left: 722px; background-position: -112px bottom !important; }

#vertgraph dl dd.a60-blank  { left: 732px; background-color: #cbcbcb; background-position: -112px bottom !important; }
#vertgraph dl dd.a60 { left: 732px; background-color: #fad163; background-position: -112px bottom !important; }
#vertgraph dl dt.a60 { left: 722px; background-position: -112px bottom !important; }



#vertgraph dl dd.a01-blank  { left: 102px; background-color: #cbcbcb; background-position: -0px bottom !important; }
#vertgraph dl dd.a01 { left: 102px; background-color: #ff3333; background-position: -0px bottom !important; }
#vertgraph dl dt.a01 { left: 14px; background-position: -0px bottom !important; }

#vertgraph dl dd.a11-blank  { left: 279px; background-color: #cbcbcb; background-position: -28px bottom !important; }
#vertgraph dl dd.a11 { left: 279px; background-color: #66cc66; background-position: -28px bottom !important; }
#vertgraph dl dt.a11 { left: 191px; background-position: -28px bottom !important; }

#vertgraph dl dd.a21-blank  { left: 456px; background-color: #cbcbcb; background-position: -56px bottom !important; }
#vertgraph dl dd.a21 { left: 456px; background-color: #66cc66; background-position: -56px bottom !important; }
#vertgraph dl dt.a21 { left: 368px; background-position: -56px bottom !important; }

#vertgraph dl dd.a31-blank  { left: 633px; background-color: #cbcbcb; background-position: -84px bottom !important; }
#vertgraph dl dd.a31 { left: 633px; background-color: #66cc66; background-position: -84px bottom !important; }
#vertgraph dl dt.a31 { left: 545px; background-position: -84px bottom !important; }

#vertgraph dl dd.a41-blank { left: 810px; background-color: #cbcbcb; background-position: -112px bottom !important; }
#vertgraph dl dd.a41 { left: 810px; background-color: #66cc66; background-position: -112px bottom !important; }
#vertgraph dl dt.a41 { left: 722px; background-position: -112px bottom !important; }

#vertgraph dl dd.a51-blank { left: 810px; background-color: #cbcbcb; background-position: -112px bottom !important; }
#vertgraph dl dd.a51 { left: 810px; background-color: #66cc66; background-position: -112px bottom !important; }
#vertgraph dl dt.a51 { left: 722px; background-position: -112px bottom !important; }

#vertgraph dl dd.a61-blank { left: 810px; background-color: #cbcbcb; background-position: -112px bottom !important; }
#vertgraph dl dd.a61 { left: 810px; background-color: #66cc66; background-position: -112px bottom !important; }
#vertgraph dl dt.a61 { left: 722px; background-position: -112px bottom !important; }

</style>



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
			<dt class="a50">4 <?=$lang["user"]["month"]?></dt>
			<dt class="a60">24 <?=$lang["user"]["month"]?></dt>
						
			<?php
				for ($i = 0; $i <= 1; $i++){
					for ($j = 0; $j <= 6; $j++){
						
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