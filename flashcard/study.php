<?php

	session_start();
	
	require_once("../private/config.php"); 
	require_once("../private/lib.php");
	require_once("../private/remember.php");
	
	if (!isset($_GET["id"])){
		echo "Not Found";
		die();
	}
	$id = $_GET["id"];
	
	if (!isset($_SESSION["username"])){
		header("Location: /login.php");
	}
	
	$con = open_con();
	
	$stmt = mysqli_prepare($con, "SELECT id, name, public, cards, category, username, UNIX_TIMESTAMP(created) AS created, url FROM sets WHERE id = ?;");
	mysqli_stmt_bind_param($stmt, "i", $id);
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);
	if (mysqli_num_rows($result) > 0) {
		$set = mysqli_fetch_assoc($result); 
	}else{
		echo "Not Found";
		mysqli_stmt_close($stmt);
		mysqli_close($con);
		die();
	}
	mysqli_stmt_close($stmt);
	
	if ($_SESSION["username"] !== $set["username"]){
		echo "Forbidden";
		mysqli_close($con);
		die();
	}
	
	$is_owner = isset($_SESSION["username"]) === true && $_SESSION["username"] === $set["username"];
	
	if (isset($_GET["all"])){
		$stmt = mysqli_prepare($con, "SELECT id, front, back FROM cards WHERE set_id = ?;");
	}else{
		$stmt = mysqli_prepare($con, "SELECT id, front, back FROM cards WHERE set_id = ? AND step <> 5 AND (weakup <= NOW() OR weakup IS NULL) ORDER BY step DESC, id;");
	}
	mysqli_stmt_bind_param($stmt, "i", $id);
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);
	$cards = array();
	if (mysqli_num_rows($result) > 0) {
		while($row = mysqli_fetch_assoc($result)) {
			$cards[] = $row;
		}
	}
	mysqli_stmt_close($stmt);
	
	$stmt = mysqli_prepare($con, "UPDATE sets SET last_used = NOW() WHERE id = ?;");
	mysqli_stmt_bind_param($stmt, "i", $id);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_close($stmt);
	
	mysqli_close($con);
	
?>
<!DOCTYPE html>
<html>
<head>
<!DOCTYPE html>
<html>
<head>
<title>Studying: <?php echo noHTML($set["name"]); ?></title>
<link rel="shortcut icon" href="/favicon.ico"  />
<link rel="stylesheet" href="<?php echo $ASSET?>/css/style.css">
</head>
<body>
<div id="wrapper">
<?php require_once("../private/navbar.php"); ?>
<div id="main">




<!-- Left -->
<div id="leftdiv">

	<!-- Title -->
	<table id="study_title"><tbody><tr>
		<td><h1>Studying: <?php echo noHTML($set["name"]); ?></h1></td>
		<td id="back"><a href="/flashcard/<?php echo $set["url"];?><?php echo $set["id"];?>" class="actionlink"><< Go to card set details</a></td>
	</tr></tbody></table>
	<!-- End title -->
	
	<div id="studysession">
	
		<!-- Ending result -->
		<div id="study_stats" class="bluegrey">
			<h1>Session Results</h1>
			<table><tbody><tr>
				<td><b>Total number of cards</b></td><td id="resultst" class="results"><?php echo sizeof($cards); ?></td>
				<td id="correct"><b>Correct</b></td><td id="resultsc" class="results">0</td>
				<td id="incorrect"><b>Incorrect</b></td><td id="resultsi" class="results">0</td>
				<td id="unanswered"><b>Unanswered</b></td><td id="resultsu" class="results"><?php echo sizeof($cards); ?></td>
			</tr></tbody></table>
			<div id="pie_div"></div>
			<div id="summary">
				<div id="back">
					<b><a href="/flashcard/<?php echo $set["url"];?><?php echo $set["id"];?>" class="actionlink">Return to Card Set >></a></b>
				</div>
			</div>
		</div>
		<!-- End ending result -->
		
		<!-- Studying result -->
		<div id="study_info">
			<span id="card_position" class="floatleft">1 / <?php echo sizeof($cards); ?></span>
			<table id="cardset_status" class="floatright"><tbody><tr>
				<td>unanswered: </td><td> <div id="totalu"><?php echo sizeof($cards); ?></div></td>
				<td>correct: </td><td><div id="totalc">0</div></td>
				<td>incorrect: </td><td><div id="totali">0</div></td>
			</tr></tbody></table>
		</div>
		<!-- End studying result -->
		
		<!-- Commands -->
		<div id="study_commands">
			<button class="smallbutton" onclick="prev_card();return false;"><table cellspacing="0"><tbody><tr><td><img src="<?php echo $ASSET; ?>/img/arrow_left.png"></td><td></td></tr></tbody></table></button>
			<button class="smallbutton" id="next" onclick="next_card();return false;"><table cellspacing="0"><tbody><tr><td><img src="<?php echo $ASSET; ?>/img/arrow_right.png"></td><td></td></tr></tbody></table></button>
			<button class="smallbutton" id="shuffle" onclick="shuffle();return false;"><table cellspacing="0"><tbody><tr><td><img src="<?php echo $ASSET; ?>/img/arrow_switch.png"></td><td>&nbsp;Shuffle!</td></tr></tbody></table></button>
			<button class="smallbutton" id="reverse" onclick="reverse();return false;"><table cellspacing="0"><tbody><tr><td><img src="<?php echo $ASSET; ?>/img/arrow_refresh.png"></td><td>&nbsp;Reverse front &amp; back</td></tr></tbody></table></button>
			<button class="smallbutton" id="end" onclick="end_session();return false;"><table cellspacing="0"><tbody><tr><td><img src="<?php echo $ASSET; ?>/img/cross.gif"></td><td>&nbsp;End session</td></tr></tbody></table></button>
		</div>
		<!-- End commands -->
		
		<!-- Display -->
		<div id="study_flashcards">
			<div id="card_status"></div>
			<table id="display"><tbody>
				<tr>
					<td id="card_front" class="studycard"><span class="ib"><p><?php if(sizeof ($cards) > 0) echo cardSide($cards[0]["front"]); ?></p></span></td>
				</tr>
				<tr>
					<td class="studycard">
					<div id="card_back"><span class="ib"><p><?php if(sizeof ($cards) > 0) echo cardSide($cards[0]["back"]); ?></p></span></div></td>
				</tr>
			</tbody></table>
		</div>
		<!-- End display -->
		
		<!-- Answer -->
		<div id="study_controls">
		
			<div id="flip">
				<button class="studybutton" onclick="flip();return false;"><table cellspacing="0"><tbody><tr><td><img src="<?php echo $ASSET; ?>/img/arrow_flip.png"></td><td>Show answer</td></tr></tbody></table></button>
			</div>
			
			<div id="mark">
				<button class="studybutton" onclick="mark_correct();return false;"><table cellspacing="0"><tbody><tr><td><img src="<?php echo $ASSET; ?>/img/markcorrect.png" border="0"></td><td> I was right</td></tr></tbody></table></button>
				<button class="studybutton" id="wrong" onclick="mark_incorrect();return false;"><table cellspacing="0"><tbody><tr><td><img src="<?php echo $ASSET; ?>/img/markincorrect.png" border="0"></td><td> I was wrong</td></tr></tbody></table></button>
			</div>
			
			<!-- <div id="grade" style="display: none;"></div> -->
		</div>
		<!-- End answer -->
		
	</div>
</div>
<!-- End left -->


<!-- Right -->
<div id="sidebar-right-study">
	<div class="box sidebar-right-box">
	
		<h3>Created:</h3>
		<?php echo timeAgo($set["created"]); ?> by <a class="userlink" href="/user/<?php echo $set["username"]; ?>"><?php echo $set["username"]; ?></a>
		<br><br>
		<div style="float:left"><h3>Number of cards: </h3></div><div style="float:left" id="cardcnt"><?php echo $set["cards"]; ?></div>
		<br><br>
		<h3>Category:</h3>
		
		<?php 
		if (!empty($set["category"])){
			if($is_owner === false){
		?>
				<a class="taglink" href="/category/<?php echo noHTML($set["category"]); ?>"><?php echo noHTML($set["category"]); ?></a>
		<?php
			} else {
		?>
				<a class="taglink"  href="/user/<?=$set["username"]?>?cat=<?=noHTML($set["category"])?>#flashcard_category"><?php echo noHTML($set["category"]); ?></a>
		<?php
			}
		}
		?>
	</div>
	<div class="box sidebar-right-box" style="line-height:25px;">
		<h3>Keyboard controls:</h3>
		Show Answer = Right Arrow<br>"I was right" = Up Arrow<br>"I was wrong" = Down Arrow<br>Go Back / Forward = Left <i>or</i> Right Arrow
	</div>
	<!--<div class="box sidebar-right-box">
		<a id="right_banner" href="https://www.vultr.com/?ref=6833778"><img src="https://www.vultr.com/media/160x600_02.gif" width="160" height="600" class="no-zoom"></a>
	</div>-->
</div>
<!-- End right -->


<br class="clearboth">
<div id="sound"></div>




</div>
</div>
<?php require_once("../private/footer.php"); ?>
<script>
	var em = false;
	var li = false;
	
	var sa = <?php if (isset($_GET["all"])){echo "true";}else{echo "false";} ?> ;
	var sound = <?php if (strpos($set["name"], "[speak-english]") !== false){echo "true";}else{echo "false";} ?> ;
	
	var ucs = false;
	var cs = 291738;
	
	var theCardset = [];	
	
	<?php 
	if (isset($cards)){
		$i = 1;
		foreach($cards as $card){
			echo sprintf('theCardset[%u] = {card_id: %u, card_front: "%s", card_back: "%s", answered: false, correct:false};', $i, $card["id"], cardSide($card["front"]), cardSide($card["back"]));
			echo "\r\n";
			$i++;
		}
	}
	?>
</script>
<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
<script src="https://www.google.com/jsapi"></script>
<script src="<?php echo $ASSET?>/js/cardsets_study.js"></script>
<?php if (sizeof($cards) == 0){ ?><script>end_session();</script><?php } ?>	
</body>
</html>