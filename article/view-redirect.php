<?php
require_once '../core.php';
$topicId = ''; if (isset($_GET['id'])) $topicId = escape($_GET['id']);
$TITLE = 'loading...'; 	
top_private();
Deck();

?>
<script>
	var topicId = '<?=$topicId?>';
	AppApi.sync.get("/card/get/by-articleid/" + topicId).then((response)=>{
		var card = response.data;
		if (card){
			var loc = "/article/learn.php?id=" + card.articleId + "&cardid=" + card.id + "&type=review";
			window.location = loc;
		} else {
			alert('nhu cc');
		}
	});
</script>
<h1>loading...</h1>
<?=bottom_private()?>