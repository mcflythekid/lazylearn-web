<?php

	session_start();
	
	require_once("./private/config.php"); 
	require_once("./private/lib.php");
	require_once("./private/remember.php");
	
	if (!isset($_GET["q"]) || empty($_GET["q"])){
		header("Location: /");
	}
	
	$con = open_con();
	
	if (isset($_GET["page"]) && is_numeric($_GET["page"]) && $_GET["page"] > 0){
		$page = $_GET["page"];
		$start_from = ($_GET["page"] - 1) * 20;
	} else{
		$page = 1;
		$start_from = 0;
	}
	
	$q = mysqli_real_escape_string($con,$_GET["q"]);
	
	$stmt = mysqli_prepare($con, "SELECT COUNT(*) AS total_set FROM searches WHERE name like '%" . $q  . "%';");
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);
	$total_set = mysqli_fetch_assoc($result)["total_set"];
	$total_page = ceil($total_set / 20);
	
	$stmt = mysqli_prepare($con, "SELECT id, name FROM searches WHERE name like '%" . $q  . "%';");
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
<title><?php echo noHTML($_GET["q"]); ?>  - Lazylearn Search</title>
<link rel="shortcut icon" href="/favicon.ico"  />
<link rel="stylesheet" href="<?php echo $ASSET?>/css/style.css">
<meta name="description" content="<?php echo noHTML($_GET["q"]); ?><?php if ($page > 1){echo " - Page " . $page;}  ?> - Lazylearn Search" />

</head>
<body>
<div id="wrapper">
<?php require_once("./private/navbar.php"); ?>
<div id="main">

	<h1 id="title">Search Result <small>(<?php echo $total_set; ?> Flashcards)</small></h1>
			<!-- Data -->
			<table><tbody>
<?php 
if(isset($sets)){
	foreach ($sets as $set){
?>
				<tr>
					<td class="cardsetlist_name set_name">
						<span class="cardsetlist_name">
							<a href="/flashcard/view.php?id=<?php echo $set["id"]; ?>">
								<?php echo noHTML($set["name"]); ?> 
							</a>
						</span>
					</td>
				</tr>
				<tr>
					<td colspan="2" class="line">&nbsp;</td>
				</tr>
<?php
	}
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
<?php require_once("./private/footer.php"); ?>
</body>
</html>