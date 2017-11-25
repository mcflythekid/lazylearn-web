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
	if (isset($_GET["username"])){
		$username = $_GET["username"];
	}
	
	if (isset($_GET["page"]) && is_numeric($_GET["page"]) && $_GET["page"] > 0){
		$page = $_GET["page"];
		$start_from = ($_GET["page"] - 1) * 20;
	} else{
		$page = 1;
		$start_from = 0;
	}

	$con = open_con();

	if (isset($username)){
		$stmt = mysqli_prepare($con, "SELECT id, name, public, cards, username, UNIX_TIMESTAMP(created) AS created, url FROM sets WHERE username = ? AND category = ? ORDER BY created DESC LIMIT 20 OFFSET ?;");
		mysqli_stmt_bind_param($stmt, "ssi", $username, $category, $start_from );
	} else {
		$stmt = mysqli_prepare($con, "SELECT id, name, public, cards, username, UNIX_TIMESTAMP(created) AS created, url FROM sets WHERE category = ? ORDER BY created DESC LIMIT 20 OFFSET ?;");
		mysqli_stmt_bind_param($stmt, "si",  $category, $start_from );
	}
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);
	if (mysqli_num_rows($result) > 0) {
		$sets = array();
		while($row = mysqli_fetch_assoc($result)) {
			$sets[] = $row;
		}
	}
	
	if (isset($username)){
		$stmt = mysqli_prepare($con, "SELECT COUNT(*) AS total_set FROM sets WHERE username = ? AND category = ?;");
		mysqli_stmt_bind_param($stmt, "ss", $username, $category);
	} else {
		$stmt = mysqli_prepare($con, "SELECT COUNT(*) AS total_set FROM sets WHERE category = ?;");
		mysqli_stmt_bind_param($stmt, "s",  $category);
	}
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);
	$total_set = mysqli_fetch_assoc($result)["total_set"];
	$total_page = ceil($total_set / 20);

	mysqli_close($con);
	
?>
<!DOCTYPE html>
<html>
<head>
<?php if (isset($username)){?>
	<title>'<?php echo noHTML($category); ?>' Flashcards of <?php echo noHTML($username); ?></title>
<?php } else {?>
	<title>'<?php echo noHTML($category); ?>' Flashcards</title>
<?php } ?>
<link rel="shortcut icon" href="/favicon.ico"  />
<link rel="stylesheet" href="<?php echo $ASSET?>/css/style.css">



<!-- Username or not -->
<?php if (isset($username)){?>

		<!-- Noindex or Index -->
		<?php if ($page == 1){ ?>
			<meta name="description" content="Flashcards tagged '<?php echo noHTML($category); ?>' of <?php echo noHTML($username); ?>" />
			<meta name="canonical" content="https://lazylearn.com/<?php echo noHTML($username); ?>/<?php echo noHTML($category); ?>"/>
		<?php } else { ?>
			<meta name="robots" content="noindex">
		<?php } ?>
		<!-- End noindex or Index -->
			
<?php } else { ?>

		<!-- Noindex or Index -->
		<?php if ($page == 1){ ?>
			<meta name="description" content="Flashcards tagged '<?php echo noHTML($category); ?>'" />
			<meta name="canonical" content="https://lazylearn.com/category/<?php echo noHTML($category); ?>"/>
		<?php } else { ?>
			<meta name="robots" content="noindex">
		<?php } ?>
		<!-- End noindex or Index -->

<?php } ?>
<!-- End username or not -->




<!-- Paginaion -->
<?php
	if (isset($username)){
		$pagiation_url = "https://lazylearn.com/user/" . noHTML($username) . "/" . noHTML($category) . "?page=";
	}else{
		$pagiation_url = "https://lazylearn.com/category/" . noHTML($category) . "?page=";
	}
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
<?php 
if (isset($username)){
?>
	<h1 id="title">Flashcards tagged '<?php echo noHTML($category); ?>' of <?php echo noHTML($username); ?></h1>
<?php
} else {
?>
	<h1 id="title">Flashcards tagged '<?php echo noHTML($category); ?>'</h1>
<?php
}
?>
	
			<table><tbody>
	
<?php 
if(isset($sets)){
	foreach ($sets as $set){
?>

			<tr>
				<td class="cardsetlist_name set_name">
					<span class="cardsetlist_name">
					
						<?php if ($set["public"] == 0){ ?><img src="<?php echo $ASSET; ?>/img/lock.gif" class="lock"><?php } ?>
					
						<a href="/flashcard/<?php echo $set['url']; ?><?php echo $set['id']; ?>"><?php echo noHTML($set["name"]); ?> 
							<span dir="ltr"><small>(<?php echo $set["cards"]; ?> cards)</small></span>
						</a>
					</span>
				</td>


				<td>
					<div class="cardsetlist_details">
						created <?php echo timeAgo($set["created"]); ?> by <a class="userlink" href="/user/<?php echo $set["username"]; ?>"><?php echo $set["username"]; ?></a>
					</div>
				</td>
			</tr>
			
			<tr>
				<td colspan="2" class="line">&nbsp;</td>
			</tr>
<?php
	}
} else {
	echo "<i>No data</i>";
}
?>
			</tbody></table>
			
			<!-- Paging -->
			<div class="pagination">
<?php
if ($total_page > 0){
	
	if (isset($username)){
		$current_url = "/user/" . noHTML($username) . "/" . noHTML($category) . "?page=";
	}else{
		$current_url = "/category/" . noHTML($category) . "?page=";
	}

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