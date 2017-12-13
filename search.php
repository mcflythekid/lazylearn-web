<?php

	session_start();
	
	require_once("./private/config.php"); 
	require_once("./private/lib.php");
	require_once("./private/remember.php");
	
	if (!isset($_GET["q"]) || empty($_GET["q"])){
		header("Location: /");
	}
	
	$con = open_con();
	
	
	$q = mysqli_real_escape_string($con,$_GET["q"]);
	
	
	$stmt = mysqli_prepare($con, "SELECT id, name FROM searches WHERE name like '%" . $q  . "%' LIMIT 30;");
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

	<h1 id="">Search Result</h1>

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
			</td>
			
		</tr>
	<?php }} ?>
	</tbody></table>
			
			
</div>	
</div>
<?php require_once("./private/footer.php"); ?>
</body>
</html>