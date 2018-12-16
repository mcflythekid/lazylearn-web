<?php
require_once '../core.php';
$TITLE = ('Article');
$HEADER = "Article";
$PATHS = [
    "Article"
];
top_private();
Article();
?>

<div class="row u-mt-20">
    <div class="col-lg-12">

        <div id="article__create--wrapper" class="admin_component" style="display: none">
            <button class="btn btn-sm btn-danger" id="article__create--btn" type="submit">** Create</button>
        </div>

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
                field: 'category',
                title: 'Category',
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
                sortable: true
            },
            {
                width: 100,
                formatter: (obj,row)=>{
                    var getHtml = ' <a class="btn btn-sm btn-success pull-left act-send-to-deck" data-article-id="' + row.id + '">GET</a> ';

					if (Application.isAdmin())
						return getHtml + 
							'<button class="btn btn-sm context-menu-button pull-right"><span class="glyphicon glyphicon-menu-hamburger"></span></button>';
					else 
						return getHtml;
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