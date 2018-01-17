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

<?php require_once("./private/navbar2.php"); ?>

<div class="row">
	<div class="col-xs-8">
		<blockquote>
				<h2>Minimum Pairs</h2>
				<p>We have just added the Minimum Pair Hacking system. Which can help people quickly have ability to Listen to English.
				<p>
				  <a class="btn btn-lg btn-primary" href="https://pair.lazylearn.com/" role="button">Try it now »</a>
				</p>
		</blockquote>
	</div>
	<div class="col-xs-4">
		<div class="fb-page" data-href="https://www.facebook.com/lazylearn/" data-small-header="true" data-adapt-container-width="true" data-hide-cover="true" data-show-facepile="true"><blockquote cite="https://www.facebook.com/lazylearn/" class="fb-xfbml-parse-ignore"><a href="https://www.facebook.com/lazylearn/">Lazylearn</a></blockquote></div>
	</div>
</div>


<?php require_once("./private/footer2.php"); ?>