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
            <button class="btn btn-success" id="article__create--btn" type="submit">Create</button>
        </div>

        <table id="article__list"></table>
        <ul id="context-menu" class="dropdown-menu">
            <li data-item="delete"><a>Delete</a></li>
            <li data-item="rename"><a>Rename</a></li>
            <li data-item="edit"><a>Edit</a></li>
        </ul>

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
        pageSize: 20,
        pageList: [20, 50, 100],
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
                    return '<a target="_blank" href="/article/view-redirect.php?id=' + row.id + '">' + o + '</a>';
                }
            },
            {
                width: 50,
                formatter: (obj,row)=>{
                    return '<button class="btn btn-sm context-menu-button pull-right"><span class="glyphicon glyphicon-menu-hamburger"></span></button>';
                }
            },
        ],
        contextMenu: '#context-menu',
        contextMenuButton: '.context-menu-button',
        contextMenuAutoClickRow: true,
        beforeContextMenuRow: function(e,row,buttonElement){
            if (Application.isAdmin()){
                $('#context-menu li[data-item="delete"]').show();
            } else {
                $('#context-menu li[data-item="delete"]').hide();
            }
            return true;
        },
        onContextMenuItem: function(row, $el) {
            if ($el.data("item") == "delete") {
                Article.delete(row.id, ()=>{
                    refresh();
                });
            } else if ($el.data("item") == "rename") {
                var newName = prompt("Please enter new name", row.name);
                if (newName){
                    AppApi.sync.post("/article/rename", {
                        articleId: row.id,
                        newName: newName
                    }).then(refresh);
                }
            } else if ($el.data("item") == "edit") {
                AppApi.sync.get("/article/get/" + row.id).then(res=>{
                    Article.openEdit(res.data.name + ' - updated', res.data.content, res.data.url);
                });
            }
        }
    });

</script>

<?=bottom_private()?>