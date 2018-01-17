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
	
	$stmt = mysqli_prepare($con, "SELECT COUNT(*) AS total_set from sets WHERE public = 1;");
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);
	$total_set = mysqli_fetch_assoc($result)["total_set"];
	$total_page = ceil($total_set / 20);
	
	$stmt = mysqli_prepare($con, "SELECT id, name, cards, username, category, UNIX_TIMESTAMP(created) AS created, url FROM sets WHERE public = 1 ORDER BY created DESC LIMIT 20 OFFSET ?;");
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
<?php require_once("../private/navbar2.php"); ?>

	
	<h1 ><?=$lang["navbar"]["browse"] ?><?php if ($page > 1) echo " #$page"; ?></h1>
	
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
				
					<?php if ($set["category"] != "" ){ ?>
						<a class="set_category" href="/flashcard/category.php?id=<?=noHTML($set["category"])?>">
							<?=$set["category"]?>
						</a>
					<?php } ?>
					
					
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
			

<?php require_once("../private/footer2.php"); ?>
