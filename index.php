<?php
	session_start();
	require_once("./private/config.php");
	require_once("./private/lib.php");
	require_once("./private/remember.php");
	$con = open_con();

	/* Get recent created */
	$stmt = mysqli_prepare($con, "SELECT id, name, cards, category, username, UNIX_TIMESTAMP(created) AS created, url FROM sets WHERE cards > 0 AND public = 1 ORDER BY created DESC LIMIT 20;");
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);
	if (mysqli_num_rows($result) > 0) {
		$sets_1 = array();
		while($row = mysqli_fetch_assoc($result)) {
			$sets_1[] = $row;
		}
	}

	/* Get recent studied*/
	$stmt = mysqli_prepare($con, "SELECT id, name, cards, category, username, UNIX_TIMESTAMP(last_used) AS last_used, url FROM sets WHERE cards > 0 AND last_used IS NOT NULL AND public = 1 ORDER BY last_used DESC LIMIT 20;");
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
		<table class="home_line"><tbody >
		<?php $first = true;if(isset($sets_1)){foreach ($sets_1 as $set){ ?>
			<tr class="home_line <?php if ($first) {$first = false; echo " first"; }?>">
				<td>
					<span class="cardsetlist_name">
						<a href="/flashcard/<?php echo $set["url"]; ?><?php echo $set["id"]; ?>">
							<?php echo noHTML($set["name"]); ?>
						</a>
					</span>
					<div class="cardsetlist_details">
						<span class="card_count"><?=$set["cards"]?> <?=$lang["user"]["cards"]?></span>
						<?php if ($set["category"] != "" ){ ?>
							<a class="set_category" href="/flashcard/category.php?id=<?=noHTML($set["category"])?>">
								<?=noHTML($set["category"])?>
							</a>
						<?php } ?>
						<?=$lang["index"]["created"] ?> <?php echo timeAgo($set["created"]); ?> <?=$lang["index"]["by"] ?> <a class="userlink" href="/user/<?php echo $set["username"]; ?>"><?php echo $set["username"]; ?></a>
						
					</div>
				</td>
				
			</tr>
		<?php }} ?>
		</tbody></table>
	</div>
	<!-- End by created time-->

	<!-- By studied time-->
	<div id="studied-cslist" class="box">
		<h2 ><?=$lang["index"]["recent_learn"] ?></h2>
		<table class="home_line"><tbody >
		<?php $first = true;if(isset($sets_2)){foreach ($sets_2 as $set){ ?>
			<tr class="home_line <?php if ($first) {$first = false; echo " first"; }?>">
				<td>
					<span class="cardsetlist_name">
						<a href="/flashcard/<?php echo $set["url"]; ?><?php echo $set["id"]; ?>">
							<?php echo noHTML($set["name"]); ?> 
						</a>
					</span>
					<div class="cardsetlist_details">
						<span class="card_count"><?=$set["cards"]?> <?=$lang["user"]["cards"]?></span>
						<?php if ($set["category"] != "" ){ ?>
							<a class="set_category" href="/flashcard/category.php?id=<?=noHTML($set["category"])?>">
								<?=noHTML($set["category"])?>
							</a>
						<?php } ?>
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
	<div id="front-taglist" class="yellow">
		<div class="tags">
			We have just added the Minimum Pair Hacking system.
			Try it now at <a href="https://pair.lazylearn.com/" >https://pair.lazylearn.com/</a>
		</div>
	</div>

	<div  id="featured" style="border:1px solid #ccc;">
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