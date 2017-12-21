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
<!DOCTYPE html>
<html>
<head>
<title><?php echo noHTML($set["name"]); ?></title>
<link rel="shortcut icon" href="/favicon.ico"  />
<link rel="stylesheet" href="<?php echo $ASSET?>/css/style.css">
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/start/jquery-ui.min.css">
<meta name="description" content="<?php echo noHTML($set["name"]); ?>" />
<meta name="canonical" content="https://lazylearn.com/flashcard/<?php echo $set['url']; ?><?php echo $set['id']; ?>"/>

<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.11.1/bootstrap-table.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.11.1/bootstrap-table.min.js"></script>
</head>
<body>
<div id="wrapper">
<?php require_once("../private/navbar.php"); ?>
<?php require_once("../private/graph.php"); ?>
<div id="main">

<h1 id="username">Flashcards: <?php echo noHTML($set["name"]); ?></h1>	

<?php graph($set["username"], $id); ?>

<!-- Left column-->
<div id="cs">

	<!-- Operations -->
	<div id="cs_details">
		
		<!-- Delete form -->
		<div id="dialog-confirm" title="Delete this card set?"></div>
		<form name="delete" action="/flashcard/delete.php" method="post" onSubmdit="return confirm('Are you sure?');" style="display: none;">
			<input type="hidden" name="id" value="<?php echo $set["id"]; ?>">
		</form>
		<!-- End delete form -->
		
		<?php if ($is_owner){?>
		
		<!-- Study options -->
		<table id="studyopt" border="0" cellspacing="0" cellpadding="0"><tbody><tr valign="top">
			
			<td>
				<a href="/flashcard/study.php?id=<?php echo $set["id"]; ?>&study_fresh" class="actionlink">
					<img alt="Study Fresh Card" src="<?php echo $ASSET; ?>/img/study_old.png" class="study_opt">
					<?=$lang["set"]["study_fresh"]?>
				</a>
			</td>
			
			<td>
				<a href="/flashcard/study.php?id=<?php echo $set["id"]; ?>&study_old" class="actionlink">
					<img alt="Study Old Card" src="<?php echo $ASSET; ?>/img/study_old.png" class="study_opt">
					<?=$lang["set"]["study_old"]?>
				</a>
			</td>
			
			<td>
				<a href="/flashcard/study.php?id=<?php echo $set["id"]; ?>" class="actionlink">
					<img alt="Spaced Repetition" src="<?php echo $ASSET; ?>/img/leitner_system_icon.png" class="study_opt">
					<?=$lang["set"]["study"]?>
				</a>
			</td>

			
			<td>
				<a href="/flashcard/study.php?id=<?php echo $set["id"]; ?>&review_all" class="actionlink">
					<img alt="All Flashcards" src="<?php echo $ASSET; ?>/img/world.png" class="study_opt">
					<?=$lang["set"]["review"]?>
				</a>
			</td>
			

			
			
		</tr></tbody></table>
		<!-- End study options -->
		
		<!-- Other options -->
		<div id="topbar">
			<div id="bc"></div>
			<div id="topcmd">
			
				<?php if (isset($_GET["compose"])){ ?>
				<img alt="Add a card" src="<?php echo $ASSET; ?>/img/add-icon.png">
				<a href="#new_front" class="actionlink"><?=$lang["set"]["add_card"]?></a>
				<?php } else {?>
				<img alt="Compose" src="<?php echo $ASSET; ?>/img/edit-icon.png">
				<a href="/flashcard/view.php?id=<?= $set["id"] ?>&compose" class="actionlink"><?=$lang["set"]["compose"]?></a>
				<?php } ?>
				
				<img alt="Export" src="<?php echo $ASSET; ?>/img/arrow_down.png">
				<a href="/flashcard/export.php?id=<?php echo $set["id"]; ?>" class="actionlink"><?=$lang["set"]["export"]?></a>
				
				<img alt="Import" src="<?php echo $ASSET; ?>/img/arrow_up.png">
				<a href="/flashcard/import.php?id=<?php echo $set["id"]; ?>" class="actionlink"><?=$lang["set"]["import"]?></a>
				
				<img alt="Setting" src="<?php echo $ASSET; ?>/img/setting.png">
				<a href="/flashcard/setting.php?id=<?php echo $set["id"]; ?>" class="actionlink"><?=$lang["set"]["setting"]?></a>
				
				<img alt="Delete" src="<?php echo $ASSET; ?>/img/cross.gif">
				<a href="javascript:delete_confirm()" class="actionlink"><?=$lang["set"]["delete"]?></a>
				
				<a class="fb-share-button" id="face_share" 
					data-href="https://lazylearn.com/flashcard/<?php echo $set["url"]; ?><?php echo $set["id"]; ?>" data-layout="button_count">
				</a>
			</div>
		</div>
		<!-- End other options -->
			
		<?php }else { /** not is owner **/?>	

		<table id="studyopt" border="0" cellspacing="0" cellpadding="0"><tbody><tr valign="top"></tr></tbody></table>

		<!-- Other options -->
		<div id="topbar">
			<div id="bc"></div>
			<div id="topcmd">
				<img alt="Save" src="<?php echo $ASSET; ?>/img/heart_add.png">
				<a href="/flashcard/clone.php?id=<?php echo $id;?>" class="actionlink">Save to my flashcards & Learn</a>
				
				<?php if (isset($_SESSION["username"]) && $_SESSION["username"] == $ROOT) { ?>
					<img alt="Delete" src="<?php echo $ASSET; ?>/img/cross.gif">
					<a href="javascript:delete_confirm()" class="actionlink"><?=$lang["set"]["delete"]?></a>
				<?php } ?>
				
			</div>
		</div>
		<!-- End other options -->
		
		<?php }?>

	</div>
	<!-- End operations -->

	
	
	<script>
	$(document).ready(function(){
		$('#cc2').bootstrapTable({
			method : 'get',
			url : './viewjax.php?id=<?=$id?>',
			cache : false,
			class : 'table table-hover',
			striped : true,
			height: 300,

			pagination : true,
			pageSize : 5,
			pageList : [ 5, 10, 20, 50, 100 ],
			singleSelect : true,
			sidePagination : 'server',
			minimumCountColumns : 2,
			clickToSelect : true,
			queryParams : function(params){
				return params;
			},									
			columns : [
				{
					field : 'front',
					title : 'Front',
					align : 'left',
					valign : 'top',
					sortable : true,
				},
				{
					field : 'back',
					title : 'Back',
					align : 'left',
					valign : 'top',
					sortable : true,
				}
			]
		});
	});
	</script>
	
	<!-- Card list -->
	<div id="card-list-container" class="cardsetlist"><div id="card-list">
	
		<div class="cardlist_card" id="loading">
			<table class="card_container"><tbody>
				<?=$lang["set"]["loading"]?>
			</tbody></table>
		</div>
	
		<a id="port"></a>
	
		<!-- New card form-->
		<?php if ($is_owner && isset($_GET["compose"])){ ?>
		<div class="cardlist_card" id="card-new">
			<table class="card_container"><tbody>
				<tr>
					<td class="card_container_leftcol"></td>
					<td class="cardlist_card_data"><textarea class="card_edit_field" id="new_front"></textarea></td>
					<td class="cardlist_card_middle"></td>
					<td class="cardlist_card_data"><textarea class="card_edit_field" id="new_back"></textarea></td>
					<td class="card_container_rightcol"></td>
				</tr>
				<tr>
					<td class="card_container_leftcolopt"></td>
					<td colspan="2" align="left">
						<button class="new_edit_submit" id="new_submit"><?=$lang["set"]["add_card"]?></button>
					</td>
					<td id="new_error" class="error"></td>
				</tr>
			</tbody></table>
		</div>
		<?php } else if (!isset($_GET["compose"])) {?>
		<div class="cardlist_card" id="card-new">
			<a href="/flashcard/view.php?id=<?= $set["id"] ?>&compose"><?=$lang["set"]["enable_compose"]?></a>
		</div>
		<?php }?>
		<!-- End new card form-->

	<!-- End card list -->
	</div></div>
				
				
	<table id="cc"></table>
				
				
</div>
<!-- End left column-->	


<!-- Right column -->
<div id="sidebar-right-cs">
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
				
	
</div>
<!-- End right column -->

<br class="clearboth">
			

</div>	
</div>
<?php require_once("../private/footer.php"); ?>
<script src="//code.jquery.com/ui/1.11.3/jquery-ui.min.js"></script>
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
	var sample = '<div class="cardlist_card" id="card-xxx"><table class="card_container" id="card_display-xxx"><tbody><tr><td class="card_container_leftcol"><img alt="Change info" src="<?php echo $ASSET; ?>/img/edit-icon.png" class="cardlist_card_data_edit" id="cardlist_card_data_edit-xxx"><br><br><img alt="Delete"src="<?php echo $ASSET; ?>/img/cross.gif" class="cardlist_card_data_delete"id="cardlist_card_data_delete-xxx"></td><td class="cardlist_card_data"><span class="ib"><p id="card-front-xxx">front-xxx</p></span></td><td class="cardlist_card_middle"></td><td class="cardlist_card_data"><span class="ib"><p id="card-back-xxx">back-xxx</p></span></td><td class="card_container_rightcol"></td></tr></tbody></table><table class="card_container_hidden" id="card_edit-xxx"><tbody><tr><td class="card_container_leftcol"><img alt="Delete" src="<?php echo $ASSET; ?>/img/cross.gif"class="cardlist_card_data_delete"id="cardlist_card_data_delete-xxx"></td><td class="cardlist_card_data"><textarea class="card_edit_field" id="edit_front-xxx"></textarea></td><td class="cardlist_card_middle"></td><td class="cardlist_card_data"><textarea class="card_edit_field" id="edit_back-xxx"></textarea></td><td class="card_container_rightcol"></td></tr><tr><td class="card_container_leftcolopt"></td><td colspan="2" align="left"><button  class="new_edit_submit" id="edit_submit-xxx">Update</button> or <a href="javascript:void(0)" id="edit_cancel-xxx">Cancel</a></td><td id="edit_error-16249" class="error"></td></tr></tbody></table></div>';		
	<?php }else { ?>
	var sample = '<div class="cardlist_card" id="card-xxx"><table class="card_container" id="card_display-xxx"><tbody><tr><td class="card_container_leftcol"></td><td class="cardlist_card_data"><span class="ib"><p id="card-front-xxx">front-xxx</p></span></td><td class="cardlist_card_middle"></td><td class="cardlist_card_data"><span class="ib"><p id="card-back-xxx">back-xxx</p></span></td><td class="card_container_rightcol"></td></tr></tbody></table></div>';		
	<?php } ?>
	
	var set_id = <?php echo $id; ?> ;
</script>

<script src="<?php echo $ASSET?>/js/cardsets_view.js"></script>
</body>
</html>