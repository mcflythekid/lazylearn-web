<?php
require_once '../core.php';
$TITLE = ('Minpair');
$HEADER = "Minpair";
$PATHS = [
    "Minpair"
];
top_private();
Minpair();
?>

<div class="row u-mt-20">
    <div class="col-lg-12">

        <div id="mminpair__create--wrapper" class="admin_component" style="display: none">
            <button class="btn btn-danger" id="mminpair__create--btn" type="submit">** Create</button>
        </div>

        <table id="minpair__list"></table>
        <ul id="context-menu" class="dropdown-menu">
            <li data-item="delete"><a>Delete</a></li>
        </ul>

    </div>
</div>

<script>
    var refresh = ()=>{
        $('#minpair__list').bootstrapTable('refresh',{
            silent: true
        });
    };

    $('#mminpair__create--btn').click((event)=> {
        Minpair.openCreate();
    });
	
	$(document).on('click', 'a.act-send-to-deck', function(e){
		Minpair.sendToDeck($(this).attr('data-deck-id'), deckObj=>{
			FlashMessage.success("Get success");
			refresh();
		});
	});

    $('#minpair__list').bootstrapTable({
        classes: 'table table-hover table-bordered table-condensed table-responsive bg-white',
        url: apiServer + "/minpair/search",
        cache: false,
        method: 'post',
        striped: false,
        toolbar: '#mminpair__create--wrapper',
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
                field: 'language',
                title: 'Language',
                sortable: true
            },
            {
                field: 'word1',
                title: 'Word 1',
                sortable: true
            },
            {
                field: 'word2',
                title: 'Word 2',
                sortable: true
            },
            {
                width: 100,
                formatter: (obj,row)=>{
                    var getHtml = ' <a class="btn btn-sm btn-success pull-left act-send-to-deck" data-deck-id="' + row.id + '">GET</a> ';

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
                Minpair.delete(row.id, ()=>{
                    refresh();
                });
            }
        }
    });

</script>

<?=bottom_private()?>