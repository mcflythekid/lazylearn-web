<?php
	session_start();
	
	require_once("../private/config.php"); 
	require_once("../private/lib.php");
	require_once("../private/remember.php");
	
	if (!isset($_GET["id"])){
		header("Location: /");
		die();
	}
	$id = $_GET["id"];
	
	$con = open_con();
	
	$stmt = mysqli_prepare($con, "SELECT id, name, public, cards, category, username, UNIX_TIMESTAMP(created) AS created, url FROM sets WHERE id = ?;");
	mysqli_stmt_bind_param($stmt, "i", $id);
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);
	if (mysqli_num_rows($result) > 0) {
		$set = mysqli_fetch_assoc($result); 
	}else{
		mysqli_close($con);
		header("Location: /");
		die();
	}
	mysqli_stmt_close($stmt);
	
	if ($set["public"] == 0 && (!isset($_SESSION["username"]) || $_SESSION["username"] !== $set["username"])){
		mysqli_close($con);
		header("Location: /");
		die();  
	}
	
	$stmt = mysqli_prepare($con, "SELECT id, front, back FROM cards WHERE set_id = ?;");
	mysqli_stmt_bind_param($stmt, "i", $id);
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);
	if (mysqli_num_rows($result) > 0) {
		$cards = array();
		while($row = mysqli_fetch_assoc($result)) {
			$cards[] = $row;
		}
	}
	mysqli_stmt_close($stmt);
	
	mysqli_close($con);
	
	$is_authenticated = isset($_SESSION["username"]);
	if ($is_authenticated){
		$is_owner = $_SESSION["username"] === $set["username"];
	}else{
		$is_owner = false;
	}
	
?>

<?php require_once("../private/navbar2.php"); ?>
<?php require_once("../private/graph.php"); ?>



<style>
.slideContainer {
    overflow-x: auto;
    white-space: nowrap;
	width: 100%;
}
.slide {
    display: inline-block;
    width: 100%;
    white-space: normal;
}
</style>




<h1>Flashcards: <?php echo noHTML($set["name"]); ?></h1>	

<a class="fb-share-button" id="face_share"  data-href="https://lazylearn.com/flashcard/<?php echo $set["url"]; ?><?php echo $set["id"]; ?>" data-layout="button_count"></a><br><br>

<div class="slideContainer">
	<div class="slide">
		<?php graph($set["username"], $id); ?>
	</div>
</div>


<!-- Delete form -->
<div id="dialog-confirm" title="Delete this card set?"></div>
<form name="delete" action="/flashcard/delete.php" method="post" onSubmdit="return confirm('Are you sure?');" style="display: none;">
	<input type="hidden" name="id" value="<?php echo $set["id"]; ?>">
</form><!-- End delete form -->

		
<?php if ($is_owner){?>
	<div class="row">
		<div class="col-lg-12">
			<div class="btn-group btn-group-justified" role="group" aria-label="Justified button group with nested dropdown"> 
				<a href="/flashcard/study.php?id=<?php echo $set["id"]; ?>&study_fresh" class="btn btn-default" role="button">
					<img alt="Study Fresh Card" src="<?php echo $ASSET; ?>/img/study_old.png" class="study_opt hidden-xs">
					<?=$lang["set"]["study_fresh"]?>
				</a>
				<a href="/flashcard/study.php?id=<?php echo $set["id"]; ?>&study_old" class="btn btn-default" role="button">
					<img alt="Study Old Card" src="<?php echo $ASSET; ?>/img/study_old.png" class="study_opt hidden-xs">
					<?=$lang["set"]["study_old"]?>
				</a>
				<a href="/flashcard/study.php?id=<?php echo $set["id"]; ?>" class="btn btn-default" role="button">
					<img alt="Spaced Repetition" src="<?php echo $ASSET; ?>/img/leitner_system_icon.png" class="study_opt hidden-xs">
					<?=$lang["set"]["study"]?>
				</a>
				<a href="/flashcard/study.php?id=<?php echo $set["id"]; ?>&review_all" class="btn btn-default" role="button">
					<img alt="All Flashcards" src="<?php echo $ASSET; ?>/img/world.png" class="study_opt hidden-xs">
					<?=$lang["set"]["review"]?>
				</a>	
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12">
			<div class="btn-group btn-group-justified" role="group" aria-label="Justified button group with nested dropdown">
				<?php if (!isset($_GET["compose"])) { ?>
					<a href="/flashcard/view.php?id=<?= $set["id"] ?>&compose"  class="btn btn-default" role="button"><?=$lang["set"]["enable_compose"]?></a>
				<?php } ?>
				<a href="/flashcard/export.php?id=<?php echo $set["id"]; ?>"  class="btn btn-default" role="button"><?=$lang["set"]["export"]?></a>
				<a href="/flashcard/import.php?id=<?php echo $set["id"]; ?>"  class="btn btn-default" role="button"><?=$lang["set"]["import"]?></a>
				<a href="/flashcard/setting.php?id=<?php echo $set["id"]; ?>" class="btn btn-default" role="button"><?=$lang["set"]["setting"]?></a>
				<a href="javascript:delete_confirm()"                         class="btn btn-default" role="button"><?=$lang["set"]["delete"]?></a>
			</div>
		</div>
	</div>
	
	<br>
	
	<?php if (isset($_GET["compose"])){ ?>
		<div class="row">
			<div class="form-group col-xs-6">
				<textarea class="form-control" id="new_front" rows="3"></textarea>
			</div>
			<div class="form-group col-xs-6">
				<textarea class="form-control" id="new_back"  rows="3"></textarea>
			</div>
			<div class="form-group col-lg-12">
				<button type="submit" id="new_submit" class="btn btn-primary pull-right"><?=$lang["set"]["add_card"]?></button>
			</div>
		</div>
	<?php } ?>
	
<?php } else { /** not is owner **/?>
	<div class="row">
		<div class="col-lg-6">
			<a href="/flashcard/clone.php?id=<?php echo $id;?>" class="btn btn-default" role="button">Learn</a>
			<?php if (isset($_SESSION["username"]) && $_SESSION["username"] == $ROOT) { ?>
				<a href="javascript:delete_confirm()" class="btn btn-default" role="button"><?=$lang["set"]["delete"]?></a>
			<?php } ?>
		</div>
	</div>
<?php }?>

<div class="row">
	<div class="col-lg-12 hidden-xs hidden-sm">
		<div id="card-list-container">
			<div id="card-list">
				<div class="cardlist_card" id="loading">
					<table class="card_container"><tbody>
						<?=$lang["set"]["loading"]?>
					</tbody></table>
				</div>
				<a id="port"></a>
			</div>
		</div>
	</div>
</div>



<script>
	var theCardset = [];	
	<?php if (isset($cards) and isset($_GET["compose"])){	
		$i = 0;
		foreach($cards as $card){
			echo sprintf('theCardset[%u] = {card_id: %u, card_front: "%s", card_back: "%s"};', $i, $card["id"], cardSide($card["front"]), cardSide($card["back"]));
			echo "\r\n";
			$i++;
	} } ?>

	<?php if ($is_owner){ ?>
	var sample = 
	
	'<div class="cardlist_card" id="card-xxx"><table class="card_container" id="card_display-xxx"><tbody><tr><td class="card_container_leftcol"><img alt="Change info" src="<?php echo $ASSET; ?>/img/edit-icon.png" class="cardlist_card_data_edit" id="cardlist_card_data_edit-xxx"><br><br><img alt="Delete"src="<?php echo $ASSET; ?>/img/cross.gif" class="cardlist_card_data_delete"id="cardlist_card_data_delete-xxx"></td><td class="cardlist_card_data"><span class="ib"><p id="card-front-xxx">front-xxx</p></span></td><td class="cardlist_card_middle"></td><td class="cardlist_card_data"><span class="ib"><p id="card-back-xxx">back-xxx</p></span></td><td class="card_container_rightcol"></td></tr></tbody></table><table class="card_container_hidden" id="card_edit-xxx"><tbody><tr><td class="card_container_leftcol"><img alt="Delete" src="<?php echo $ASSET; ?>/img/cross.gif"class="cardlist_card_data_delete"id="cardlist_card_data_delete-xxx"></td><td class="cardlist_card_data"><textarea class="card_edit_field" id="edit_front-xxx"></textarea></td><td class="cardlist_card_middle"></td><td class="cardlist_card_data"><textarea class="card_edit_field" id="edit_back-xxx"></textarea></td><td class="card_container_rightcol"></td></tr><tr><td class="card_container_leftcolopt"></td><td colspan="2" align="left"><button  class="new_edit_submit" id="edit_submit-xxx">Update</button> or <a href="javascript:void(0)" id="edit_cancel-xxx">Cancel</a></td><td id="edit_error-16249" class="error"></td></tr></tbody></table></div>';		
	<?php }else { ?>
	var sample = '<div class="cardlist_card" id="card-xxx"><table class="card_container" id="card_display-xxx"><tbody><tr><td class="card_container_leftcol"></td><td class="cardlist_card_data"><span class="ib"><p id="card-front-xxx">front-xxx</p></span></td><td class="cardlist_card_middle"></td><td class="cardlist_card_data"><span class="ib"><p id="card-back-xxx">back-xxx</p></span></td><td class="card_container_rightcol"></td></tr></tbody></table></div>';		
	<?php } ?>
	
	var set_id = <?php echo $id; ?> ;
</script>

<script src="<?php echo $ASSET?>/js/cardsets_view.js"></script>



<?php require_once("../private/footer2.php"); ?>