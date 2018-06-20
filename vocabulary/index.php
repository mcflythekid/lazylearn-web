<?php
require_once '../core.php';
$TITLE = 'Vocabulary';
$HEADER = "Vocabulary";
$PATHS = [
    "Vocabulary"
];
top_private();
Vocabdeck();
?>

<div class="row u-mt-20">
    <div class="col-lg-12">
        <div id="vocabdeck-create-form-wrapper">
            <form id="vocabdeck-create-form">
                <div class="input-group" >
                    <input type="text" class="form-control" id="vocabdeck-create-name" required placeholder="Vocabulary deck name...">
                    <span class="input-group-btn">
						<button class="btn btn-primary" type="submit">Create</button>
					</span>
                </div>
            </form>
        </div>
        <table id="vocabdeck-list"></table>
        <ul id="context-menu" class="dropdown-menu">
            <li data-item="archive"><a>Archive</a></li>
            <li data-item="unarchive"><a>Unarchive</a></li>
            <li data-item="rename"><a>Rename</a></li>
            <li data-item="delete"><a>Delete</a></li>
        </ul>
    </div>
</div>

<script>
    var refresh = ()=>{
        $('#vocabdeck-list').bootstrapTable('refresh',{
            silent: true
        });
    };

    $('#vocabdeck-create-form').submit((event)=> {
        event.preventDefault();
        Vocabdeck.create($('#vocabdeck-create-name').val().trim(), ()=>{
            $('#vocabdeck-create-name').val('');
            refresh();
        })
    });

    $('#vocabdeck-list').bootstrapTable({
        classes: 'table table-hover table-bordered table-condensed table-responsive bg-white',
        url: apiServer + "/vocabdeck/search",
        cache: false,
        method: 'post',
        striped: false,
        toolbar: '#vocabdeck-create-form-wrapper',
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
                formatter: (obj,row)=>{
                    return '<a href="/vocabulary/view.php?id=' + row.id + '">' + obj+'</a>' +
                        (row.archived == 1 ? ' <span class="archived">Archived</span>' : '');
                }
            },
            {
                field: 'createdDate',
                title: 'Created Date',
                sortable: true
            },
            {
                width: 50,
                formatter: (obj,row)=>{
                    return  '<button class="btn btn-sm context-menu-button pull-right"><span class="glyphicon glyphicon-menu-hamburger"></span></button>';
                }
            },
        ],
        contextMenu: '#context-menu',
        contextMenuButton: '.context-menu-button',
        contextMenuAutoClickRow: true,
        beforeContextMenuRow: function(e,row,buttonElement){
            if (row.archived == 0){
                $('#context-menu li[data-item="archive"]').show();
                $('#context-menu li[data-item="unarchive"]').hide();
            } else {
                $('#context-menu li[data-item="archive"]').hide();
                $('#context-menu li[data-item="unarchive"]').show();
            }
            return true;
        },
        onContextMenuItem: function(row, $el) {
            if        ($el.data("item") == "rename") {
                Vocabdeck.openRename(row.id, refresh);
            } else if ($el.data("item") == "delete") {
                Vocabdeck.drop(row.id, refresh);
            } else if ($el.data("item") == "archive") {
                Vocabdeck.archive(row.id, refresh);
            } else if ($el.data("item") == "unarchive") {
                Vocabdeck.unarchive(row.id, refresh);
            }
        }
    });

</script>

<?=bottom_private()?>