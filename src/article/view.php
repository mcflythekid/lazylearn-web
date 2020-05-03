<?php
require '../core.php';
require '../lang/core.php';
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

    <div class="row" style="display: none;" id="art_url">
        <div class="col-lg-12">
            <a href="" target="_blank"></a>
        </div>
    </div>

    <div class="row u-mt-20" style="display: none;" id="art_submit">
        <div class="col-lg-12">
            <button class="btn btn-flat btn-danger" id="delete">DELETE</button>
        </div>
    </div>

    <div class="row u-mt-10">
        <div class="col-lg-7">
            <div id="art_content"></div>
        </div>
    </div>

    <script>
        var articleId = '<?=$articleId?>';

        
        var reload = ()=>{
            Article.get(articleId, (article)=>{
                $('#art_submit').show();
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


        $(document).on('click', '#art_submit #delete', function(){
            Dialog.confirm('Are you sure to delete?', ()=>{
                AppApi.sync.post("/article/delete/" + articleId).then(()=>{
                    window.location = '/article'
                });
            });
        });

		
        reload();

    </script>

<?=bottom_private()?>