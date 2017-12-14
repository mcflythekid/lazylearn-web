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
	
	if (isset($_GET["review_all"])){
		$stmt = mysqli_prepare($con, "SELECT id, front, back FROM cards WHERE set_id = ?;");
		mysqli_stmt_bind_param($stmt, "i", $id);
	}else if (isset($_GET["study_old"])){
		$stmt = mysqli_prepare($con, "SELECT id, front, back FROM cards WHERE set_id = ? AND step <= ? AND weakup <= NOW() ORDER BY step DESC, id;");
		mysqli_stmt_bind_param($stmt, "ii", $id, $LEITNER_SIZE);
	}else{
		$stmt = mysqli_prepare($con, "SELECT id, front, back FROM cards WHERE set_id = ? AND step <= ? AND (weakup <= NOW() OR weakup IS NULL) ORDER BY step DESC, id;");
		mysqli_stmt_bind_param($stmt, "ii", $id, $LEITNER_SIZE);
	}
	
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
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/start/jquery-ui.min.css">
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
		<td><h1 id="set_name">Studying: <?php echo noHTML($set["name"]); ?></h1></td>
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
			
			<button class="smallbutton" id="edit" onclick="edit_card();return false;"><table cellspacing="0"><tbody><tr><td><img src="<?php echo $ASSET; ?>/img/edit-icon.png"></td><td>&nbsp;Edit</td></tr></tbody></table></button>
			<button class="smallbutton" id="delete" onclick="delete_card();return false;"><table cellspacing="0"><tbody><tr><td><img src="<?php echo $ASSET; ?>/img/cross.gif"></td><td>&nbsp;Delete</td></tr></tbody></table></button>
						
			
			<button class="smallbutton" id="end" onclick="end_session();return false;"><table cellspacing="0"><tbody><tr><td><img src="<?php echo $ASSET; ?>/img/stop.png"></td><td>&nbsp;End session</td></tr></tbody></table></button>
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

		<?=$lang["set"]["created"] ?>
		<?php echo timeAgo($set["created"]); ?> 
		<?=$lang["index"]["by"] ?>
		<a class="userlink" href="/user/<?php echo $set["username"]; ?>"><?php echo $set["username"]; ?></a>
		<br>
		<span class="card_count"><?=$set["cards"]?> <?=$lang["user"]["cards"]?></span>
		
		<?php if ($set["category"] != "" ){ ?>
			<a class="set_category" href="/flashcard/category.php?id=<?=noHTML($set["category"])?>">
				<?=$set["category"]?>
			</a>
		<?php } ?>
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
<script src="//code.jquery.com/ui/1.11.3/jquery-ui.min.js"></script>
<script src="https://www.google.com/jsapi"></script>
<script src="<?php echo $ASSET?>/js/cardsets_study.js"></script>
<?php if (sizeof($cards) == 0){ ?><script>end_session();</script><?php } ?>	




<!-- The Modal -->
<div id="myModal" class="modal">

  <!-- Modal content -->
  <div class="modal-content">
  
    Edit<span class="close">&times;</span><br><br>
    <textarea id="edit_front" rows="5" cols="36"> </textarea>
    <textarea id="edit_back" rows="5" cols="36"> </textarea>
	<input type="hidden" id="edit_id" >
	<hr>
	<button id="edit_submit">Submit</button>
  </div>

</div>

<script>

	var delete_card = function(){
		console.log(theCardset);
		if (confirm("Are you sure? This action cannot be undone!")){
			$.post("/jax/delete_card.php", {id : theCardset[currcard].card_id} );
			location.reload();
		}
		
	};

	
	
	// Get the modal
var modal = document.getElementById('myModal');

// Get the button that opens the modal
var btn = document.getElementById("edit");

var submit = document.getElementById("edit_submit");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks the button, open the modal 
btn.onclick = function() {
	if (reverse_mode ){
		return;
	}
	edit_mode = true;
    modal.style.display = "block";
	$('#edit_front').val(theCardset[currcard].card_front);
	$('#edit_back').val(theCardset[currcard].card_back);
	$('#edit_id').val(theCardset[currcard].card_id);
}

// When the user clicks the button, open the modal 
submit.onclick = function(e) {
	var front = $('#edit_front').val().trim();
	var back = $('#edit_back').val().trim();
	var id = $('#edit_id').val();
	if (!front || !back || !id){
		return;
	};
	theCardset[currcard].card_front = front;
	theCardset[currcard].card_back = back;
	$('#card_front').html(front);
	$('#card_back').html(back);
	$.post("/jax/update_card.php", {id : id, front: front, back: back} );
	edit_mode = false;
    modal.style.display = "none";
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
	edit_mode = false;
    modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
		edit_mode = false;
        modal.style.display = "none";
    }
}
</script>
<style>
/* The Modal (background) */
.modal {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 1; /* Sit on top */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgb(0,0,0); /* Fallback color */
    background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* Modal Content/Box */
.modal-content {
    background-color: #fefefe;
    margin: 15% auto; /* 15% from the top and centered */
    padding: 20px;
    border: 1px solid #888;
    width: 700px; /* Could be more or less, depending on screen size */
}

/* The Close Button */
.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close:hover,
.close:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
}
</style>

<style>
	.oxford_link, .oxford_link *{
		cursor:pointer;
		color:blue;
		text-decoration:underline;
	}
</style>
<script>
	$(document).ready(function(){
		oxford_check();
	});
	$(document).on('oxford.check' , function(){
		oxford_check();
	});
	var oxford_check = function(){
		var is_english = function($el){
			return $el.text().match(/^[a-z]+$/);
		};
		var set_name = $('#set_name').text();
		if (!set_name.includes("[oxford]")) return;
		var $el = null;
		if (is_english($('#card_front'))) $el = $('#card_front');
		if (is_english($('#card_back'))) $el = $('#card_back');
		if (!$el) return;
		$('#card_front').parent().removeClass('oxford_link').off();
		$('#card_back').parent().removeClass('oxford_link').off();
		$el.parent().addClass('oxford_link');
		$el.parent().click(function(){
			var win = window.open('https://www.oxfordlearnersdictionaries.com/us/definition/english/' + $el.text(), '_blank');
			win.focus();
		});
	};

</script>

</body>
</html>