<?php
require_once '../core.php';
$TITLE = ('Admin: Topic');
$HEADER = "Admin: Topic";
$PATHS = [
    "Admin: Topic"
];
top_private();
Article();
?>

<div class="row u-mt-20">
    <div class="col-lg-12">
        <table id="article__list"></table>
        <ul id="context-menu" class="dropdown-menu">
            <li data-item="delete"><a>Delete</a></li>
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
        url: apiServer + "/article/admin/search",
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
                field: 'user.fullName',
                title: 'User',
                sortable: true
            },
            {
                field: 'name',
                title: 'Name',
                sortable: true
            },
            {
                field: 'url',
                title: 'URL',
                sortable: true,
                formatter: (obj,row)=>{
                    return "<a target='_blank' href='" + obj + "' class=''>" + obj + "</a>";
                }
            },
            {
                width: 50,
                formatter: ()=>{
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
            }
        }
    });

</script>

<?=bottom_private()?>