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
						<button class="btn btn-info btn-flat" type="submit">Create</button>
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
                title: 'Created',
                sortable: true
            },
            {
                width: 250,
                formatter: (obj,row)=>{
                    return '<span class="vocab-menu">' + 
                        '<button data-id="' + row.id + '" class="action-rename btn btn-info btn-flat">Rename</button>' +
                        (row.archived == 0 ? '<button data-id="' + row.id + '" class="action-archive btn btn-info btn-flat u-ml-5">Archive</button>' : '' ) +
                        (row.archived == 1 ? '<button data-id="' + row.id + '" class="action-unarchive btn btn-info btn-flat u-ml-5">Unarchive</button>' : '' ) +
                        '<button data-id="' + row.id + '" class="action-delete btn btn-danger btn-flat u-ml-5">Delete</button>' + 
                    '</span>';
                }
            },
        ]
    });

    $(document).on('click', 'span.vocab-menu button.action-rename', function(event){
        const id = $(this).attr("data-id");
        Vocabdeck.openRename(id, refresh);
    });
    $(document).on('click', 'span.vocab-menu button.action-archive', function(event){
        const id = $(this).attr("data-id");
        Vocabdeck.archive(id, refresh);
    });
    $(document).on('click', 'span.vocab-menu button.action-unarchive', function(event){
        const id = $(this).attr("data-id");
        Vocabdeck.unarchive(id, refresh);
    });
    $(document).on('click', 'span.vocab-menu button.action-delete', function(event){
        const id = $(this).attr("data-id");
        Vocabdeck.delete(id, refresh);
    });

</script>

<?=bottom_private()?>