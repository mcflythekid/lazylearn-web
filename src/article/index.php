<?php
require_once '../core.php';
require_once '../lang/core.php';
$TITLE = $lang["page.topic.title"];
$HEADER = $lang["page.topic.header"];
$PATHS = [
    $lang["page.topic.header"]
];
top_private();
Article();
?>

<div class="row u-mt-20">
    <div class="col-lg-12">
        <div id="article__create--wrapper">
            <button class="btn btn-info btn-flat" id="article__create--btn" type="submit"><?= $lang["page.topic.btn.create"] ?></button>
        </div>
        <table id="article__list"></table>
    </div>
</div>

<script>
    var refresh = ()=>{
        $('#article__list').bootstrapTable('refresh',{
            silent: true
        });
    };

    $('#article__create--btn').click((event)=> {
        Article.openCreate();
    });

    $('#article__list').bootstrapTable({
        classes: 'table table-hover table-bordered table-condensed table-responsive bg-white',
        url: apiServer + "/article/search",
        cache: false,
        method: 'post',
        striped: false,
        toolbar: '#article__create--wrapper',
        sidePagination: 'server',
        sortName: 'createdDate',
        sortOrder: 'desc',
        pageSize: 100,
        pageList: [100, 150, 200],
        formatSearch: ()=> { return '<?= $lang["page.topic.input.search.holder"] ?>' },
        search: true,
        ajaxOptions: {
            headers: {
                Authorization: AppApi.getAuthorization()
            }
        },
        pagination: true,
        columns: [
            {
                field: 'name',
                title: '<?= $lang["page.topic.column.name"] ?>',
                sortable: true,
                formatter: (o, row)=>{
                    return '<a href="/article/view.php?id=' + row.id + '">' + o + '</a>';
                }
            },
            {
                field: 'url',
                title: '<?= $lang["page.topic.column.url"] ?>',
                sortable: true,
                formatter: (o)=>{
                    return '<a href="' + o + '">' + o + '</a>';
                }
            },
            {
                field: 'createdDate',
                title: '<?= $lang["common.created_date"] ?>',
                sortable: true
            },
            {
                width: 250,
                formatter: (obj,row)=>{
                    return '<span class="article-menu">' + 
                        '<button data-id="' + row.id + '" class="action-rename btn btn-info btn-flat"><?= $lang["common.rename"] ?></button>' +
                        '<button data-id="' + row.id + '" class="action-edit btn btn-info btn-flat u-ml-5"><?= $lang["common.edit"] ?></button>' +
                        '<button data-id="' + row.id + '" class="action-delete btn btn-danger btn-flat u-ml-5"><?= $lang["common.delete"] ?></button>' + 
                    '</span>';
                }
            },
        ]
    });
    $(document).on('click', 'span.article-menu button.action-rename', function(event){
        const id = $(this).attr("data-id");
        const newName = prompt("Please enter new name", "");
        if (newName){
            AppApi.sync.post("/article/rename", {
                articleId: id,
                newName: newName
            }).then(refresh);
        }
    });
    $(document).on('click', 'span.article-menu button.action-edit', function(event){
        const id = $(this).attr("data-id");
        AppApi.sync.get("/article/get/" + id).then(res=>{
            Article.openEdit(res.data.name, res.data.content, res.data.url, id);
        });
    });
    $(document).on('click', 'span.article-menu button.action-delete', function(event){
        const id = $(this).attr("data-id");
        Article.delete(id, ()=>{
            refresh();
        });
    });

</script>

<?=bottom_private()?>