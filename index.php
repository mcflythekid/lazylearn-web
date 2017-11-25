<?php
	session_start();
	require_once("./private/config.php");
	require_once("./private/lib.php");
	require_once("./private/remember.php");
	$con = open_con();

	/* Get recent created */
	$stmt = mysqli_prepare($con, "SELECT id, name, public, cards, category, username, UNIX_TIMESTAMP(created) AS created, url FROM sets WHERE cards > 0 ORDER BY created DESC LIMIT 10;");
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);
	if (mysqli_num_rows($result) > 0) {
		$sets_1 = array();
		while($row = mysqli_fetch_assoc($result)) {
			$sets_1[] = $row;
		}
	}

	/* Get recent studied*/
	$stmt = mysqli_prepare($con, "SELECT id, name, public, cards, category, username, UNIX_TIMESTAMP(last_used) AS last_used, url FROM sets WHERE cards > 0 AND last_used IS NOT NULL ORDER BY last_used DESC LIMIT 10;");
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);
	if (mysqli_num_rows($result) > 0) {
		$sets_2 = array();
		while($row = mysqli_fetch_assoc($result)) {
			$sets_2[] = $row;
		}
	}

	mysqli_close($con);
?>
<!DOCTYPE html>
<html>
<head>

<?php if (isset($_SESSION["username"])){ ?>
<title>Lazylearn</title>
<?php } else { ?>
<title><?=$lang["index"]["welcome"] ?></title>
<?php } ?>

<link rel="shortcut icon" href="/favicon.ico"  />
<link rel="stylesheet" href="<?php echo $ASSET?>/css/style.css">
<meta name="description" content="Lazylearn help you learning faster with flashcard." />
<meta name="canonical" content="https://lazylearn.com"/>
</head>
<body>
<div id="wrapper">
<?php require_once("./private/navbar.php"); ?>
<div id="main">

<!-- Left column-->
<div id="front-left">

	<!-- By created time-->
	<div id="created-cslist" class="box">
		<h2><?=$lang["index"]["recent_add"] ?></h2>
		<table><tbody >
		<?php  if(isset($sets_1)){foreach ($sets_1 as $set){ ?>
			<tr>
				<td>
					<span class="cardsetlist_name">
						<?php if ($set["public"] == 0){ ?><img src="<?php echo $ASSET; ?>/img/lock.gif" class="lock"><?php } ?>
						<a href="/flashcard/<?php echo $set["url"]; ?><?php echo $set["id"]; ?>"><?php echo noHTML($set["name"]); ?> 
							<span dir="ltr"><small>(<?php echo $set["cards"]; ?> <?=$lang["index"]["cards"] ?>)</small></span>
						</a>
					</span>
					<div class="cardsetlist_details">
						<?=$lang["index"]["created"] ?> <?php echo timeAgo($set["created"]); ?> <?=$lang["index"]["by"] ?> <a class="userlink" href="/user/<?php echo $set["username"]; ?>"><?php echo $set["username"]; ?></a>
					</div>
				</td>
				
			</tr>
		<?php }} ?>
		</tbody></table>
		<a  class="actionlink" href="/flashcard/"><small><?=$lang["index"]["browse_all"] ?></small></a>
	</div>
	<!-- End by created time-->

	<!-- By studied time-->
	<div id="studied-cslist" class="box">
		<h2 ><?=$lang["index"]["recent_learn"] ?></h2>
		<table><tbody >
		<?php if(isset($sets_2)){foreach ($sets_2 as $set){ ?>
			<tr>
				<td>
					<span class="cardsetlist_name">
						<?php if ($set["public"] == 0){ ?><img src="<?php echo $ASSET; ?>/img/lock.gif" class="lock"><?php } ?>
						<a href="/flashcard/<?php echo $set["url"]; ?><?php echo $set["id"]; ?>"><?php echo noHTML($set["name"]); ?> 
							<span dir="ltr"><small>(<?php echo $set["cards"]; ?> <?=$lang["index"]["cards"] ?>)</small></span>
						</a>
					</span>
					<div class="cardsetlist_details">
						<?=$lang["index"]["studied"] ?> <?php echo timeAgo($set["last_used"]); ?> <?=$lang["index"]["by"] ?> <a class="userlink" href="/user/<?php echo $set["username"]; ?>"><?php echo $set["username"]; ?></a>
					</div>
				</td>
				
			</tr>
		<?php }} ?>
		</tbody></table>
	</div>
	<!-- End by studied time-->

</div>
<!-- End left column-->

<!-- Right column -->
<div>
	<!--<div id="front-taglist" class="blue">
		<div  class="tags">
			<h2>Categories</h2>
			<h3>Languages</h3>
			<a class="taglink" href="/category/Vocabulary">Vocabulary</a>
		</div>
	</div>-->
	<style>
	.yellow2{
		border:1px solid #ccc;
	}
	</style>
	<div  id="featured" class="yellow2">
		<!--<h2 >Featured Flashcards</h2>-->
		<!--<table ><tbody ><tr ><td ><span  class="cardsetlist_name">-->
			<!--<a target="_blank" href="https://www.facebook.com/lazylearn"><?=$lang["index"]["on_facebook"] ?></a>-->
			
			<!--<a target="_blank" href="https://m.facebook.com/messages/compose?ids=1097550316933339"><?=$lang["index"]["msg_facebook"] ?></a>
		--><!--</span></td></tr></tbody></table>-->
	
		
<div id ="featuredz" class="fb-page" data-href="https://www.facebook.com/lazylearn/" data-small-header="true" data-adapt-container-width="true" data-hide-cover="true" data-show-facepile="true"><blockquote cite="https://www.facebook.com/lazylearn/" class="fb-xfbml-parse-ignore"><a href="https://www.facebook.com/lazylearn/">Lazylearn</a></blockquote></div>
	</div>
	
	
</div>
<!-- End right column -->

<div class="clearboth"></div>

</div>
</div>
<?php require_once("./private/footer.php"); ?>
</body>
</html>