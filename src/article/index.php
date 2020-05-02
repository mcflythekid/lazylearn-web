<?php
require_once '../core.php';
$TITLE = ('Topic');
$HEADER = "Topic";
$PATHS = [
    "Topic"
];
top_private();
Article();
?>

<div class="row u-mt-20">
    <div class="col-lg-12">
        <div id="article__create--wrapper">
            <button class="btn btn-info btn-flat" id="article__create--btn" type="submit">Create</button>
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
	
	$(document).on('click', 'a.act-send-to-deck', function(e){
		Article.sendToDeck($(this).attr('data-article-id'), deckObj=>{
			FlashMessage.success("Get success");
			refresh();
		});
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
                title: 'Name',
                sortable: true,
                formatter: (o, row)=>{
                    return '<a target="_blank" href="/article/view.php?id=' + row.id + '">' + o + '</a>';
                }
            },
            {
                field: 'createdDate',
                title: 'Created',
                sortable: true
            },
            {
                width: 250,
                formatter: (obj,row)=>{
                    return '<span class="article-menu">' + 
                        '<button data-id="' + row.id + '" class="action-rename btn btn-info btn-flat">Rename</button>' +
                        '<button data-id="' + row.id + '" class="action-edit btn btn-info btn-flat u-ml-5">Edit</button>' +
                        '<button data-id="' + row.id + '" class="action-delete btn btn-danger btn-flat u-ml-5">Delete</button>' + 
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