<?php
require_once '../core.php';
$deckId = ''; if (isset($_GET['id'])) $deckId = escape($_GET['id']);
$learnType = ''; if (isset($_GET['type'])) $learnType = escape($_GET['type']);
$TITLE = 'loading...'; 	
top_private();
Deck();

?>
<script>
	var deckId = '<?=$deckId?>';
	var learnType = '<?=$learnType?>';
	AppApi.sync.get("/learn/get-deck?deckId=" + deckId + "&learnType=" + learnType).then((response)=>{

		if (!response.data.cards.length){
			Dialog.fail("No expired items to learn", ()=>{
				window.location = Constant.deckUrl;
			});
		}
		
		var card = response.data.cards[0];
		var loc = "/minpair/learn.php?id=" + card.front + "&cardid=" + card.id + "&type=" + learnType;
		window.location = loc;
	});
</script>
<h1>loading...</h1>
<?=bottom_private()?>