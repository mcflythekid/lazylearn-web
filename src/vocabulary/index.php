<?php
require_once '../core.php';
require_once '../lang/core.php';
$TITLE = $lang["page.vocabdeck.title"];
$HEADER = $lang["page.vocabdeck.header"];
$PATHS = [
    $lang["page.vocabdeck.header"]
];
top_private();
Vocabdeck();
?>

<div class="row u-mt-20">
    <div class="col-lg-12">
        <div id="vocabdeck-create-form-wrapper">
            <form id="vocabdeck-create-form">
                <div class="input-group" >
                    <input type="text" class="form-control" id="vocabdeck-create-name" required placeholder="<?= $lang["page.vocabdeck.input.create.holder"] ?>">
                    <span class="input-group-btn">
						<button class="btn btn-info btn-flat" type="submit"><?= $lang["page.vocabdeck.btn.create"] ?></button>
					</span>
                </div>
            </form>
        </div>
        <table id="vocabdeck-list"></table>
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
        formatSearch: ()=> { return '<?= $lang["page.vocabdeck.input.search.holder"] ?>' },
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
                title: '<?= $lang["page.vocabdeck.column.name"] ?>',
                sortable: true,
                formatter: (obj,row)=>{
                    return '<a href="/vocabulary/view.php?id=' + row.id + '">' + obj+'</a>' +
                        (row.archived == 1 ? ' <span class="archived">Archived</span>' : '');
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
                    return '<span class="vocab-menu">' + 
                        '<button data-id="' + row.id + '" class="action-rename btn btn-info btn-flat"><?= $lang["common.rename"] ?></button>' +
                        (row.archived == 0 ? '<button data-id="' + row.id + '" class="action-archive btn btn-info btn-flat u-ml-5"><?= $lang["common.archive"] ?></button>' : '' ) +
                        (row.archived == 1 ? '<button data-id="' + row.id + '" class="action-unarchive btn btn-info btn-flat u-ml-5"><?= $lang["common.unarchive"] ?></button>' : '' ) +
                        '<button data-id="' + row.id + '" class="action-delete btn btn-danger btn-flat u-ml-5"><?= $lang["common.delete"] ?></button>' + 
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