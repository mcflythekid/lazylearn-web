<?php
require '../core.php';
$articleId = ''; if (isset($_GET['id'])) $articleId = escape($_GET['id']);
$cardId = ''; if (isset($_GET['cardid'])) $cardId = escape($_GET['cardid']);
$learnType = ''; if (isset($_GET['type'])) $learnType = escape($_GET['type']);
$TITLE = 'loading...';
$HEADER = '<span id="appHeader">loading..</span>';
$PATHS = [
    ["/article", "Article"],
    '<span id="appBreadcrumb1">loading..</span>'
];
top_private();
Article();
?>

    <div class="row" style="display: none;" id="art_submit">
        <div class="col-lg-12">
            <button class="btn btn-sm btn-success" id="article__create--btn" type="submit">Complete</button>
        </div>
    </div>

    <div class="row" style="display: none;" id="art_url">
        <div class="col-lg-12">
            <h4>URL: <a href="" target="_blank"></a></h4>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div id="art_content"></div>
        </div>
    </div>

    <script>
        var articleId = '<?=$articleId?>';
        var cardId = '<?=$cardId?>';
        var learnType = '<?=$learnType?>';		
        var cardObj;
        
		AppApi.sync.get('/card/get/' + cardId).then(res=>{
            cardObj = res.data;
            $('#art_submit').show();
		});

        var reload = ()=>{
            Article.get(articleId, (article)=>{
                count = correct = incorrect = 0;
                $('#appHeader').text(article.name);
                $('#art_content').html(article.content);
                if(article.url){
                    $('#art_url').show();
                    $('#art_url a').text(article.url);
                    $('#art_url a').attr("href", article.url);
                }
                document.title = article.name;
                $('#appBreadcrumb1').text(article.name);
            }, err=>{
				Dialog.fail("Article not found");
			});
        };

        var goHome = ()=>{
            window.location = Constant.deckUrl;
        };

        $(document).on('click', '#art_submit button', function(e){
            e.preventDefault();
            Dialog.confirm('This cannot be undone', ()=>{
                if (learnType === 'learn'){
					AppApi.sync.post("/learn/correct/" + cardId).then(()=>{
                        goHome();
                    });
				} else if (learnType === 'review' && cardObj.step == 0){
					AppApi.sync.post("/learn/correct/" + cardId).then(()=>{
                        goHome();
                    });
				} else {
                    goHome();
                }
            });
        });
		
        reload();

    </script>

<?=bottom_private()?>