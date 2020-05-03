<?php
require '../core.php';
require '../lang/core.php';

$slug = ''; if (isset($_GET['slug'])) $slug = escape($_GET['slug']);

$TITLE = $lang["common.loading"];

top_public();
?>
    <h3 id="art_name"><?= $lang["common.loading"] ?></h3>

    <div class="row u-mt-10">
        <div class="col-lg-7">
            <div id="art_content"></div>
        </div>
    </div>

    <div class="u-mt-30">
        <div id="bonus"></div>
        <div class="u-mt-5">
            <a class="nounderline" href="/"><strong><?= $lang["page.blog.back"] ?></strong></a>
        </div>
        
    </div>
    
    <script>
        var slug = '<?= $slug ?>';
        console.log("slug:", slug);
        if (!slug){
            $('#art_name').html('<?= $lang["common.not_found"] ?>');
            document.title = '<?= $lang["common.not_found"] ?>';
        } else {
            AppApi.sync("/article/public/" + slug).then(res=>{
                const article = res.data;
                document.title = article.name;
                $('#art_name').html(article.name);
                $('#art_content').html(article.content);
            }).catch(()=>{
                $('#art_name').html('<?= $lang["common.not_found"] ?>');
                document.title = '<?= $lang["common.not_found"] ?>';
            })
        }

        AppApi.async("/article/public/randoms/" + slug).then(res=>{
            const arr = res.data;
            console.log(arr);
            arr.forEach(item=>{
                $("#bonus").append("<div class='u-mt-5'><a class='nounderline' href='/blog/" + item.slug + "'>" + item.name + "</a></div>")
            })
        });

    </script>

<?=bottom_public()?>