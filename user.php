<?php
	session_start();
	require_once("./private/config.php"); 
	require_once("./private/lib.php");
	require_once("./private/remember.php");
	

	if (!isset($_GET["id"])){
		header("Location: /");
		die();
	}
	$username = $_GET["id"];
	

	$con = open_con();
	

	$stmt = mysqli_prepare($con, "SELECT COUNT(*) AS itok FROM users WHERE username = ?;");
	mysqli_stmt_bind_param($stmt, "s", $username);
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);
	$row = mysqli_fetch_assoc($result);
	if ($row["itok"] == 0){
		mysqli_stmt_close($stmt);
		mysqli_close($con);
		session_destroy();
		echo "Not Found";
		die();
	}
	mysqli_stmt_close($stmt);
	

	$data = array();
	
	/* Now left (Expired) */
	$stmt = mysqli_prepare($con, "SELECT COUNT(*) AS data FROM cards WHERE username = ? AND step = 0 AND weakup IS NULL;");
	mysqli_stmt_bind_param($stmt, "s", $username);
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);
	$row = mysqli_fetch_assoc($result);
	$data[0][0] = $row["data"];
	mysqli_stmt_close($stmt);
	
	/* Now right */
	$stmt = mysqli_prepare($con, "SELECT COUNT(*) AS data FROM cards WHERE username = ? AND step = 0 AND weakup IS NOT NULL;");
	mysqli_stmt_bind_param($stmt, "s", $username);
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);
	$row = mysqli_fetch_assoc($result);
	$data[0][1] = $row["data"];
	mysqli_stmt_close($stmt);
	
	
	
	for ($i = 1; $i <= $LEITNER_SIZE + 1; $i++) {
		
		/* Left (Expired) */
		$stmt = mysqli_prepare($con, "SELECT COUNT(*) AS data FROM cards WHERE username = ? AND step = ? AND weakup <= NOW();");
		mysqli_stmt_bind_param($stmt, "si", $username, $i);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		$row = mysqli_fetch_assoc($result);
		$data[$i][0] = $row["data"];
		mysqli_stmt_close($stmt);
		
		/* Right */
		$stmt = mysqli_prepare($con, "SELECT COUNT(*) AS data FROM cards WHERE username = ? AND step = ?;");
		mysqli_stmt_bind_param($stmt, "si", $username, $i);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		$row = mysqli_fetch_assoc($result);
		if ($i == $LEITNER_SIZE + 1){
			$data[$i][1] = $row["data"];
		} else {
			$data[$i][1] = $row["data"] - $data[$i][0];
		}
		mysqli_stmt_close($stmt);
	
	}
	
	/* Get sets data */
	$stmt = mysqli_prepare($con, "SELECT
	 s.id, s.name, s.public, s.cards, UNIX_TIMESTAMP(s.created) AS created, s.url, s.category, COUNT(c.id) as repetition
     FROM sets s
	 LEFT JOIN cards c ON s.id = c.set_id AND c.step <= ? AND (c.weakup <= NOW() OR c.weakup IS NULL)
	 WHERE s.username = ?
	 GROUP BY s.id, s.name, s.public, s.cards, s.created, s.url, s.category 
	 ORDER BY created DESC;");
	mysqli_stmt_bind_param($stmt, "is", $LEITNER_SIZE, $username);
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);
	if (mysqli_num_rows($result) > 0) {
		
		/* Create array */
		$sets = array();
		
		while($row = mysqli_fetch_assoc($result)) {
			$sets[] = $row;
		}

	}
	mysqli_stmt_close($stmt);
	
	/* Get time up data */
	$stmt = mysqli_prepare($con, "SELECT COUNT(c.id) AS timeup
     FROM sets s
	 LEFT JOIN cards c ON s.id = c.set_id AND c.step <= ? AND (c.weakup <= NOW() OR c.weakup IS NULL)
	 WHERE s.username = ?
	 GROUP BY s.id
	 ORDER BY created DESC;");
	mysqli_stmt_bind_param($stmt, "is", $LEITNER_SIZE, $username);
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);
	if (mysqli_num_rows($result) > 0) {
		/* Create array */
		$sets_timeup = array();
		while($row = mysqli_fetch_assoc($result)) {
			$sets_timeup[] = $row;
		}
	}
	mysqli_stmt_close($stmt);
	
	/* Get learned today count*/
	$stmt = mysqli_prepare($con, "SELECT COUNT(c.id) AS learned
     FROM sets s
	 LEFT JOIN cards c ON s.id = c.set_id AND c.step = 0 AND (c.weakup <= NOW() OR c.weakup IS NULL) AND (c.learned IS NOT NULL AND DATE(learned) = CURDATE())
	 WHERE s.username = ?
	 GROUP BY s.id
	 ORDER BY created DESC;");
	mysqli_stmt_bind_param($stmt, "s", $username);
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);
	if (mysqli_num_rows($result) > 0) {
		/* Create array */
		$sets_learned = array();
		while($row = mysqli_fetch_assoc($result)) {
			$sets_learned[] = $row;
		}
	}
	mysqli_stmt_close($stmt);
	
	/* Get categories */
	$stmt = mysqli_prepare($con, "SELECT DISTINCT(category) AS name FROM sets WHERE username = ?;");
	mysqli_stmt_bind_param($stmt, "s", $username);
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);
	if (mysqli_num_rows($result) > 0) {
		
		$categories = array();
		
		while($row = mysqli_fetch_assoc($result)) {
			if ($row["name"] !== ""){
				$categories[] = $row["name"];
			}
		}

	}
	mysqli_stmt_close($stmt);
	
	mysqli_close($con);
	
?>
<!DOCTYPE html>
<html>
<head>
<title>
	<?=isset($_GET["cat"]) ? noHtml($_GET["cat"]) . " Flashcards | " : ""?>
	<?php echo $username; ?>
</title>
<link rel="shortcut icon" href="/favicon.ico"  />
<link rel="stylesheet" href="<?php echo $ASSET?>/css/style.css">
<meta name="description" content="Flashcards of <?php echo $username; ?>" />
<meta name="canonical" content="https://lazylearn.com/user/<?php echo $username; ?>"/>





</head>
<body>
<div id="wrapper">
<?php require_once("./private/navbar.php"); ?>
<div id="main">



<?php
$C_COL_WIDTH = 50;
$C_WIDTH = 911;
$c_col_count = ($LEITNER_SIZE + 2) * 2 - 1;
$c_space_count = $LEITNER_SIZE + 3;
$c_space_width_all = $C_WIDTH -    $c_col_count * $C_COL_WIDTH   -     2 * ($LEITNER_SIZE + 1 );
$c_space_width = $c_space_width_all / $c_space_count;

$col_first = array();
$col_second = array();
$col_cap= array();
$col_first[0] = - $C_COL_WIDTH*2 - 2;
for ($i = 1; $i <= $LEITNER_SIZE + 2; $i++) {
	$col_first[$i] = $col_first[$i - 1] + $c_space_width + $C_COL_WIDTH * 2 + 2;
	
	
	$col_cap[$i] = $col_first[$i];
	$col_second[$i] = $col_first[$i] + $C_COL_WIDTH + 2;
}
?>
<script>
	var c_col_count = <?=$c_col_count ?>;
	var c_space_count = <?=$c_space_count ?>;
	var c_space_width_all = <?=$c_space_width_all ?>;
	var c_space_width = <?= $c_space_width?>;
</script>


<style>
/* Graph column */
#vertgraph dl dd { 
	position: absolute; width: <?=$C_COL_WIDTH?>px; height: 110px; bottom: 34px; 
	padding: 0 !important; margin: 0 !important; text-align: center; font-size: .9em; 
	text-decoration: none;font-weight: bold;color: white;line-height: 1.5em;
}
/* -------------------------------------------------------------------------------------------- */

/* Caption */
#vertgraph dl dt { 
	position: absolute; width: <?=$C_COL_WIDTH*2+2?>px; height: 25px; bottom: 0px; 
	padding: 0 !important; margin: 0 !important; text-align: center; color: #444444; font-size: .8em;
}
#vertgraph dl dt.dt_mcfly{ width: <?=$C_COL_WIDTH?>px; }

#vertgraph dl dt.a00 { left: <?=$col_cap[1]?>px;  }
#vertgraph dl dt.a10 { left: <?=$col_cap[2]?>px; }
#vertgraph dl dt.a20 { left: <?=$col_cap[3]?>px; }
#vertgraph dl dt.a30 { left: <?=$col_cap[4]?>px;}
#vertgraph dl dt.a40 { left: <?=$col_cap[5]?>px;  }
#vertgraph dl dt.a50 { left: <?=$col_cap[6]?>px;  }
#vertgraph dl dt.a60 { left: <?=$col_cap[7]?>px; }
#vertgraph dl dt.a70 { left: <?=$col_cap[8]?>px; }
/* -------------------------------------------------------------------------------------------- */

/* First */
#vertgraph dl dd.a00-blank  { left: <?=$col_first[1]?>px; background-color: #cbcbcb;}
#vertgraph dl dd.a00 { left: <?=$col_first[1]?>px; background-color: #fad163; }

#vertgraph dl dd.a10-blank  { left: <?=$col_first[2]?>px; background-color: #cbcbcb; }
#vertgraph dl dd.a10 { left: <?=$col_first[2]?>px; background-color: #fad163; }

#vertgraph dl dd.a20-blank  { left: <?=$col_first[3]?>px; background-color: #cbcbcb; }
#vertgraph dl dd.a20 { left: <?=$col_first[3]?>px; background-color: #fad163; }

#vertgraph dl dd.a30-blank  { left: <?=$col_first[4]?>px; background-color: #cbcbcb; }
#vertgraph dl dd.a30 { left: <?=$col_first[4]?>px; background-color: #fad163;  }

#vertgraph dl dd.a40-blank  { left: <?=$col_first[5]?>px; background-color: #cbcbcb; }
#vertgraph dl dd.a40 { left: <?=$col_first[5]?>px; background-color: #fad163;  }

#vertgraph dl dd.a50-blank  { left: <?=$col_first[6]?>px; background-color: #cbcbcb;  }
#vertgraph dl dd.a50 { left: <?=$col_first[6]?>px; background-color: #fad163;}

#vertgraph dl dd.a60-blank  { left: <?=$col_first[7]?>px; background-color: #cbcbcb; }
#vertgraph dl dd.a60 { left: <?=$col_first[7]?>px; background-color: #fad163;  }

#vertgraph dl dd.a70-blank  { left: <?=$col_first[8]?>px; background-color: #cbcbcb; }
#vertgraph dl dd.a70 { left: <?=$col_first[8]?>px; background-color: #66cc66;  }
/* -------------------------------------------------------------------------------------------- */

/* Second */
#vertgraph dl dd.a01-blank  { left: <?=$col_second[1]?>px; background-color: #cbcbcb;  }
#vertgraph dl dd.a01 { left: <?=$col_second[1]?>px; background-color: #ff3333;  }

#vertgraph dl dd.a11-blank  { left: <?=$col_second[2]?>px; background-color: #cbcbcb;  }
#vertgraph dl dd.a11 { left: <?=$col_second[2]?>px; background-color: #66cc66;  }

#vertgraph dl dd.a21-blank  { left: <?=$col_second[3]?>px; background-color: #cbcbcb;  }
#vertgraph dl dd.a21 { left: <?=$col_second[3]?>px; background-color: #66cc66;  }

#vertgraph dl dd.a31-blank  { left: <?=$col_second[4]?>px; background-color: #cbcbcb;  }
#vertgraph dl dd.a31 { left: <?=$col_second[4]?>px; background-color: #66cc66;  }

#vertgraph dl dd.a41-blank { left: <?=$col_second[5]?>px; background-color: #cbcbcb;  }
#vertgraph dl dd.a41 { left: <?=$col_second[5]?>px; background-color: #66cc66;  }

#vertgraph dl dd.a51-blank { left: <?=$col_second[6]?>px; background-color: #cbcbcb;  }
#vertgraph dl dd.a51 { left: <?=$col_second[6]?>px; background-color: #66cc66;  }

#vertgraph dl dd.a61-blank { left: <?=$col_second[7]?>px; background-color: #cbcbcb;  }
#vertgraph dl dd.a61 { left: <?=$col_second[7]?>px; background-color: #66cc66;  }

#vertgraph dl dd.a71-blank { left: <?=$col_second[8]?>px; background-color: #cbcbcb;  }
#vertgraph dl dd.a71 { left: <?=$col_second[8]?>px; background-color: #66cc66;  }
/* -------------------------------------------------------------------------------------------- */

/* Air space */
#vertgraph dl dd.background{ height: 110px;background: none; }
</style>







<h1 id="username"><?=noHtml( $_GET["id"]) ?></h1>

<!-- Graph -->
<div id="vertgraph-father">
	<div id="vertgraph">
		<div id="legend">
			<div id="legendleft"><?=$lang["user"]["today"]?>: <span class="green"><?php echo ($data[0][0] + $data[1][0] + $data[2][0] + $data[3][0] + $data[4][0] + $data[5][0] + $data[6][0] + $data[0][1]);?></span></div>
			<div id="legendright">
			<?=$lang["user"]["expired"]?>: <span class="legsp" id="expired"><?php echo ($data[0][0] + $data[1][0] + $data[2][0] + $data[3][0] + $data[4][0] + $data[5][0] + $data[6][0]);?></span>
			<?=$lang["user"]["correct"]?>: <span class="legsp" id="correct"><?php echo ($data[1][1] + $data[2][1] + $data[3][1] + $data[4][1] + $data[5][1] + $data[6][1] + + $data[7][1]);?></span><!-- include LIMIT-->
			<?=$lang["user"]["incorrect"]?>: <span class="legsp" id="incorrect"><?php echo $data[0][1];?></span></div>
		</div>

		<dl>
			<dt class="a00"><?=$lang["user"]["now"]?></dt>
			<dt class="a10"><?=$lang["user"]["tomorrow"]?></dt>
			<dt class="a20">3 <?=$lang["user"]["days"]?></dt>
			<dt class="a30">1 <?=$lang["user"]["week"]?></dt>
			<dt class="a40">1 <?=$lang["user"]["month"]?></dt>
			<dt class="a50">4 <?=$lang["user"]["months"]?></dt>
			<dt class="a60">2 <?=$lang["user"]["years"]?></dt>
			<dt class="a70 dt_mcfly">McFly</dt>
						
			<?php
				for ($i = 0; $i <= 1; $i++){
					for ($j = 0; $j <= $LEITNER_SIZE + 1; $j++){
						
						if ($j == $LEITNER_SIZE + 1){
							if ($i == 0){
								continue;
							} else {
								$background_css_class = "a" . $j . 0;
							}
						} else {
							$background_css_class = "a" . $j . $i;
						}
						
						$card_number = $data[$j][$i];
						
						if ($card_number == 0){
							$data_css_class = $background_css_class . "-blank";
						} else  {
							$data_css_class = $background_css_class;
						}
						
						$height = floor($card_number / 3) + 1;
						
						if ($height > 110){
							$height = 110;
						}
			?>
			<dd class="<?php echo $background_css_class; ?> background" title="<?php echo $card_number;?> <?=$lang["user"]["cards"]?>"></dd>
			<dd class="<?php echo $data_css_class; ?>" style="height: <?php echo $height;?>px;" title="<?php echo $card_number;?> <?=$lang["user"]["cards"]?>">
				<?php if ($card_number >= 50){ echo $card_number; }?>
			</dd>
			<?php } } ?>				

		</dl>
	</div>
</div>
<!-- End graph -->


<br>
<div class="box">
	<h2><?=$lang["user"]["flashcards"]?></h2>

	<table><tbody>
		<?php  if(isset($sets)){ for ($i = 0; $i < sizeof($sets); $i++){ $set = $sets[$i];?>
		<tr><td>
		
			<span class="cardsetlist_name">
				

				<?php if ($set["public"] == 0){ ?>
					<img src="<?php echo $ASSET; ?>/img/lock.gif" class="lock" alt="Private" title="Private">
				<?php } else {?>
					<img src="<?php echo $ASSET; ?>/img/world.png" class="lock" alt="Public" title="Public">
				<?php } ?>
				
				<?php if ($sets_learned[$i]['learned'] == $sets_timeup[$i]['timeup']){ ?>
					<img src="<?php echo $ASSET; ?>/img/learned.png" class="lock" alt="Learned all today!" title="Learned all today!">
				<?php } ?>					
				
				<?php if ($set["repetition"] > 0){ ?>
					<img src="<?php echo $ASSET; ?>/img/alarm.png" class="lock" alt="Time up" title="Time up">
				<?php } ?>
				
			
				
				<span class="card_count"><?=$set["cards"]?> <?=$lang["user"]["cards"]?></span>
				
				<?php if ($set["category"] != "" ){ ?>
					<a class="set_category" href="/flashcard/category.php?id=<?=noHTML($set["category"])?>">
						<?=$set["category"]?>
					</a>
				<?php } ?>
				
				
				<a href="/flashcard/<?php echo $set["url"]; ?><?php echo $set["id"]; ?>">
					<span><?=noHtml($set["name"])?></span>
				</a>

			</span>
			
		</td></tr>
		<?php } }else{ echo "<i></i>"; } ?>
						
	</tbody></table>
		
</div>
<!-- End sets -->




</div>
</div>
<?php require_once("./private/footer.php"); ?>
</body>
</html>