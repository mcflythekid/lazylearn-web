<?php

	session_start();
	
	require_once("../private/config.php"); 
	require_once("../private/lib.php");
	require_once("../private/remember.php");
	
	$con = open_con();
	
	if (isset($_GET["page"]) && is_numeric($_GET["page"]) && $_GET["page"] > 0){
		$page = $_GET["page"];
		$start_from = ($_GET["page"] - 1) * 20;
	} else{
		$page = 1;
		$start_from = 0;
	}
	
	$stmt = mysqli_prepare($con, "SELECT COUNT(*) AS total_set from sets;");
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);
	$total_set = mysqli_fetch_assoc($result)["total_set"];
	$total_page = ceil($total_set / 20);
	
	$stmt = mysqli_prepare($con, "SELECT id, name, public, cards, category, username, UNIX_TIMESTAMP(created) AS created, url FROM sets ORDER BY created DESC LIMIT 20 OFFSET ?;");
	mysqli_stmt_bind_param($stmt, "i", $start_from);
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);
	if (mysqli_num_rows($result) > 0) {
		$sets = array();
		while($row = mysqli_fetch_assoc($result)) {
			$sets[] = $row;
		}
	}
	
	mysqli_close($con);
?>
<!DOCTYPE html>
<html>
<head>
<title>All Flashcards <?php if ($page > 1) echo "[$page]"; ?></title>
<link rel="shortcut icon" href="/favicon.ico"  />
<link rel="stylesheet" href="<?php echo $ASSET?>/css/style.css">
<meta name="description" content="Browse flashcards" />

<!-- Noindex or Index -->
<?php if ($page == 1){ ?>
<meta name="canonical" content="https://lazylearn.com/flashcard/"/>
<?php } else { ?>
<meta name="robots" content="noindex">
<?php } ?>
<!-- End noindex or Index -->

<!-- Paginaion -->
<?php if ($total_page > 0){ if ($page == 1 && $page < $total_page) { ?>
<link rel="next" href="https://lazylearn.com/flashcard/?page=<?php echo $page + 1; ?>" />
<?php  } else if ($page < $total_page) { ?>
<link rel="prev" href="https://lazylearn.com/flashcard/?page=<?php echo $page - 1; ?>" />
<link rel="next" href="https://lazylearn.com/flashcard/?page=<?php echo $page + 1; ?>" />
<?php  } else if ($page == $total_page && $page > 1) { ?>
<link rel="prev" href="https://lazylearn.com/flashcard/?page=<?php echo $page - 1; ?>" />
<?php  } } ?>
<!-- End paginaion -->

</head>
<body>
<div id="wrapper">
<?php require_once("../private/navbar.php"); ?>
<div id="main">
	
			<h1 id="title">Recently Created <?php if ($page > 1) echo "- Page $page"; ?></h1>
			
			<!-- Data -->
			<table><tbody>
<?php 
if(isset($sets)){
	foreach ($sets as $set){
?>
				<tr>
					<td class="cardsetlist_name set_name">
						<span class="cardsetlist_name">
							<?php if ($set["public"] == 0){ ?><img src="<?php echo $ASSET; ?>/img/lock.gif" class="lock"><?php } ?>
							<a href="/flashcard/<?php echo $set["url"]; ?><?php echo $set["id"]; ?>"><?php echo noHTML($set["name"]); ?> 
								<span dir="ltr"><small>(<?php echo $set["cards"]; ?> cards)</small></span>
							</a>
						</span>
					</td>
					<td>
						<div class="cardsetlist_details">
							created  <?php echo timeAgo($set["created"]); ?> by <a class="userlink" href="/user/<?php echo $set["username"]; ?>"><?php echo $set["username"]; ?></a>
						</div>
					</td>
				</tr>
<?php 
		if($set["category"]){
?>
				<tr>
					<td colspan="2" class="set_category">
						<small><?php echo noHTML($set["category"]); ?></small>
					</td>
				</tr
<?php 
		}
?>
				<tr>
					<td colspan="2" class="line">&nbsp;</td>
				</tr>
<?php
	}
}else{
	echo "<i>No data</i>";
}
?>
			</tbody></table>
			
			
			<!-- Paging -->
			<div class="pagination">
<?php
if ($total_page > 0){
	if ($page == 1 && $page < $total_page) {
?>
				<span class="disabled prev_page">« Previous</span>
				<a class="next_page" href="./?page=<?php echo $page + 1; ?>">Next »</a>
<?php 
	} else if ($page < $total_page) {
?>
				<a class="prev_page" href="./?page=<?php echo $page - 1; ?>">« Previous</a>
				<a class="next_page" href="./?page=<?php echo $page + 1; ?>">Next »</a>
<?php 
	} else if ($page == $total_page && $page > 1) {
?>
				<a class="prev_page" href="./?page=<?php echo $page - 1; ?>">« Previous</a>
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