<?php

	session_start();

	require_once("../private/config.php"); 
	require_once("../private/lib.php");
	require_once("../private/remember.php");

	if (!isset($_GET["id"])){
		echo "Not Found";
		die();
	}
	$category = $_GET["id"];
	
	if (isset($_GET["page"]) && is_numeric($_GET["page"]) && $_GET["page"] > 0){
		$page = $_GET["page"];
		$start_from = ($_GET["page"] - 1) * 20;
	} else{
		$page = 1;
		$start_from = 0;
	}

	$con = open_con();


	$stmt = mysqli_prepare($con, "SELECT id, name, public, cards, username, UNIX_TIMESTAMP(created) AS created, url FROM sets WHERE category = ? ORDER BY created DESC LIMIT 20 OFFSET ?;");
	mysqli_stmt_bind_param($stmt, "si",  $category, $start_from );

	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);
	if (mysqli_num_rows($result) > 0) {
		$sets = array();
		while($row = mysqli_fetch_assoc($result)) {
			$sets[] = $row;
		}
	}
	

	$stmt = mysqli_prepare($con, "SELECT COUNT(*) AS total_set FROM sets WHERE category = ?;");
	mysqli_stmt_bind_param($stmt, "s",  $category);

	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);
	$total_set = mysqli_fetch_assoc($result)["total_set"];
	$total_page = ceil($total_set / 20);

	mysqli_close($con);
	
?>
<!DOCTYPE html>
<html>
<head>
	<title>'<?php echo noHTML($category); ?>' Flashcards</title>

<link rel="shortcut icon" href="/favicon.ico"  />
<link rel="stylesheet" href="<?php echo $ASSET?>/css/style.css">





<!-- Noindex or Index -->
<?php if ($page == 1){ ?>
	<meta name="description" content="Flashcards tagged '<?php echo noHTML($category); ?>'" />
<?php } else { ?>
	<meta name="robots" content="noindex">
<?php } ?>
<!-- End noindex or Index -->





<!-- Paginaion -->
<?php
	$pagiation_url = "https://lazylearn.com/category/" . noHTML($category) . "?page=";
?>	

<?php if ($total_page > 0){ if ($page == 1 && $page < $total_page) { ?>
<link rel="next" href="<?php echo $pagiation_url . ($page + 1); ?>" />
<?php  } else if ($page < $total_page) { ?>
<link rel="prev" href="<?php echo $pagiation_url . ($page - 1); ?>" />
<link rel="next" href="<?php echo $pagiation_url . ($page + 1); ?>" />
<?php  } else if ($page == $total_page && $page > 1) { ?>
<link rel="prev" href="<?php echo $pagiation_url . ($page - 1); ?>" />
<?php  } } ?>
<!-- End paginaion -->

</head>
<body>
<div id="wrapper">
<?php require_once("../private/navbar.php"); ?>
<div id="main">


	<!-- Header -->	
	<h1 id="">Flashcards tagged '<?php echo noHTML($category); ?>'</h1>

	
	<!-- Data -->
	<table class="home_line"><tbody >
	<?php $first = true;if(isset($sets)){foreach ($sets as $set){ ?>
		<tr class="home_line <?php if ($first) {$first = false; echo " first"; }?>">
			<td>
				<span class="cardsetlist_name">
					<a href="/flashcard/<?php echo $set["url"]; ?><?php echo $set["id"]; ?>">
						<?php echo noHTML($set["name"]); ?>
					</a>
				</span>
				<div class="cardsetlist_details">
				
					<span class="card_count"><?=$set["cards"]?> <?=$lang["user"]["cards"]?></span>
							
		
							
					<?=$lang["index"]["created"] ?> <?php echo timeAgo($set["created"]); ?> <?=$lang["index"]["by"] ?> <a class="userlink" href="/user/<?php echo $set["username"]; ?>"><?php echo $set["username"]; ?></a>

				</div>
			</td>
			
		</tr>
	<?php }} ?>
	</tbody></table>
			
	<!-- Paging -->
	<div class="pagination">
		<?php
		if ($total_page > 0){
			

			$current_url = "/flashcard/category.php?id=" . noHTML($category) . "&page=";

			if ($page == 1 && $page < $total_page) {
		?>
						<span class="disabled prev_page">« Previous</span>
						<a class="next_page" href="<?php echo $current_url . ($page + 1); ?>">Next »</a>
		<?php 
			} else if ($page < $total_page) {
		?>
						<a class="prev_page" href="<?php echo $current_url . ($page - 1); ?>">« Previous</a>
						<a class="next_page" href="<?php echo $current_url . ($page + 1); ?>">Next »</a>
		<?php 
			} else if ($page == $total_page && $page > 1) {
		?>
						<a class="prev_page" href="<?php echo $current_url . ($page - 1); ?>">« Previous</a>
						<span class="disabled next_page">Next »</span>
		<?php 
			}
		}
		?>
	</div>
			
			
			

</div>
</div>
<?php require_once("../private/footer.php"); ?>
</body>
</html>